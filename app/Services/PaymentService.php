<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\PaymentNotice;
use Carbon\Carbon;

class PaymentService
{
    /**
     * Generate payment notices for all active customers.
     */
    public function generateMonthlyNotices(): int
    {
        $customers = Customer::with('plan')
            ->whereNotNull('plan_id')
            ->where('plan_status', 'active')
            ->whereNotNull('plan_installed_at')
            ->get();

        $noticesCreated = 0;

        foreach ($customers as $customer) {
            if ($this->shouldGenerateNotice($customer)) {
                $this->createPaymentNotice($customer);
                $noticesCreated++;
            }
        }

        return $noticesCreated;
    }

    /**
     * Check if a customer needs a new payment notice.
     */
    protected function shouldGenerateNotice(Customer $customer): bool
    {
        $nextBillingDate = $customer->getNextBillingDate();
        
        if (!$nextBillingDate) {
            return false;
        }

        // Check if notice already exists for this period
        $existingNotice = $customer->paymentNotices()
            ->where('due_date', $nextBillingDate)
            ->whereIn('status', ['pending', 'overdue'])
            ->exists();

        // Only generate if no existing notice and due date is within next 30 days
        return !$existingNotice && $nextBillingDate <= Carbon::now()->addDays(30);
    }

    /**
     * Create a payment notice for a customer.
     */
    public function createPaymentNotice(Customer $customer): PaymentNotice
    {
        $dueDate = $customer->getNextBillingDate();
        $periodFrom = $dueDate->copy()->subMonth();
        $periodTo = $dueDate->copy()->subDay();

        return PaymentNotice::create([
            'customer_id' => $customer->id,
            'plan_id' => $customer->plan_id,
            'due_date' => $dueDate,
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'amount_due' => $customer->plan->monthly_rate,
            'status' => 'pending',
        ]);
    }

    /**
     * Record a customer payment.
     */
    public function recordPayment(
        Customer $customer,
        float $amount,
        string $paymentMethod,
        Carbon $paymentDate,
        int $monthsCovered = 1,
        ?string $referenceNumber = null,
        ?string $notes = null
    ): CustomerPayment {
        // Calculate expected amount based on monthly rate and months covered
        $expectedAmount = $customer->plan->monthly_rate * $monthsCovered;
        
        // Determine the period this payment covers using discrete month logic
        $lastPayment = $customer->payments()
            ->confirmed()
            ->orderBy('period_to', 'desc')
            ->first();

        if ($lastPayment) {
            $periodFrom = $lastPayment->period_to->addDay();
        } else {
            $periodFrom = $customer->plan_installed_at ?: $paymentDate;
        }

        // Calculate period_to to cover exactly the specified number of discrete months
        $currentMonth = $periodFrom->copy()->startOfMonth();
        $lastMonth = $currentMonth->copy()->addMonths($monthsCovered - 1);
        $periodTo = $lastMonth->copy()->endOfMonth();

        $payment = CustomerPayment::create([
            'customer_id' => $customer->id,
            'plan_id' => $customer->plan_id,
            'amount' => $amount,
            'plan_rate' => $customer->plan->monthly_rate,
            'payment_date' => $paymentDate,
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'payment_method' => $paymentMethod,
            'reference_number' => $referenceNumber,
            'notes' => $notes,
            'status' => 'confirmed',
        ]);

        // Mark relevant payment notices as paid
        $this->markNoticesAsPaid($customer, $periodFrom, $periodTo, $paymentDate);

        return $payment;
    }

    /**
     * Mark payment notices as paid for the covered period.
     */
    protected function markNoticesAsPaid(Customer $customer, Carbon $periodFrom, Carbon $periodTo, Carbon $paidDate)
    {
        $notices = $customer->paymentNotices()
            ->where(function ($query) use ($periodFrom, $periodTo) {
                $query->whereBetween('due_date', [$periodFrom, $periodTo])
                      ->orWhere(function ($q) use ($periodFrom, $periodTo) {
                          $q->where('period_from', '>=', $periodFrom)
                            ->where('period_to', '<=', $periodTo);
                      });
            })
            ->whereIn('status', ['pending', 'overdue'])
            ->get();

        foreach ($notices as $notice) {
            $notice->markAsPaid($paidDate);
        }
    }

    /**
     * Update overdue statuses for all payment notices.
     */
    public function updateOverdueStatuses()
    {
        $overdueNotices = PaymentNotice::pending()
            ->where('due_date', '<', Carbon::now())
            ->get();

        foreach ($overdueNotices as $notice) {
            $notice->updateOverdueStatus();
        }

        return $overdueNotices->count();
    }

    /**
     * Recalculate all payment notices from installation dates.
     * This will generate notices for all months from installation to current date.
     */
    public function recalculateFromInstallationDates()
    {
        $customers = Customer::whereNotNull('plan_installed_at')
            ->whereHas('plan')
            ->with('plan')
            ->get();

        $totalNotices = 0;
        $currentDate = Carbon::now();

        foreach ($customers as $customer) {
            $installationDate = Carbon::parse($customer->plan_installed_at);
            
            // Start from the month after installation
            $billingMonth = $installationDate->copy()->addMonth()->startOfMonth();
            
            // Generate notices until current month + 1 (for next month's notice)
            $maxDate = $currentDate->copy()->addMonth()->endOfMonth();
            
            while ($billingMonth <= $maxDate) {
                $dueDate = $billingMonth->copy();
                $periodFrom = $billingMonth->copy()->subMonth();
                $periodTo = $billingMonth->copy()->subDay();
                
                // Check if notice already exists
                $existingNotice = $customer->paymentNotices()
                    ->where('due_date', $dueDate->format('Y-m-d'))
                    ->exists();
                
                if (!$existingNotice) {
                    PaymentNotice::create([
                        'customer_id' => $customer->id,
                        'plan_id' => $customer->plan_id,
                        'due_date' => $dueDate,
                        'period_from' => $periodFrom,
                        'period_to' => $periodTo,
                        'amount_due' => $customer->plan->monthly_rate,
                        'status' => $dueDate < $currentDate ? 'overdue' : 'pending',
                    ]);
                    
                    $totalNotices++;
                }
                
                $billingMonth->addMonth();
                
                // Safety check to prevent infinite loop
                if ($billingMonth->year > $currentDate->year + 2) {
                    break;
                }
            }
        }

        return $totalNotices;
    }

    /**
     * Get payment summary for a customer.
     */
    public function getCustomerPaymentSummary(Customer $customer): array
    {
        $totalPaid = $customer->payments()->confirmed()->sum('amount');
        $overdueNotices = $customer->getOverdueNotices();
        $overdueAmount = $overdueNotices->sum('amount_due');
        $nextDueDate = $customer->getNextBillingDate();
        $balance = $customer->getPaymentBalance();

        return [
            'total_paid' => $totalPaid,
            'overdue_amount' => $overdueAmount,
            'overdue_count' => $overdueNotices->count(),
            'next_due_date' => $nextDueDate,
            'balance' => $balance,
            'is_advance' => $balance < 0,
            'status' => $this->getPaymentStatus($customer),
        ];
    }

    /**
     * Get payment status for a customer.
     */
    protected function getPaymentStatus(Customer $customer): string
    {
        $overdueNotices = $customer->getOverdueNotices();
        
        if ($overdueNotices->isNotEmpty()) {
            return 'overdue';
        }

        $balance = $customer->getPaymentBalance();
        
        if ($balance < 0) {
            return 'advance';
        } elseif ($balance == 0) {
            return 'current';
        } else {
            return 'pending';
        }
    }

    /**
     * Update payment notices when installation date changes.
     * This will remove existing notices and regenerate them based on the new installation date.
     */
    public function updateNoticesForInstallationDateChange(Customer $customer): int
    {
        if (!$customer->plan_installed_at || !$customer->plan) {
            return 0;
        }

        // Delete existing unpaid notices for this customer
        $deletedNotices = $customer->paymentNotices()
            ->whereIn('status', ['pending', 'overdue'])
            ->delete();

        // Regenerate notices from the new installation date
        $installationDate = Carbon::parse($customer->plan_installed_at);
        $currentDate = Carbon::now();
        $noticesCreated = 0;
        
        // Start from the month after installation
        $billingMonth = $installationDate->copy()->addMonth()->startOfMonth();
        
        // Generate notices until current month + 1 (for next month's notice)
        $maxDate = $currentDate->copy()->addMonth()->endOfMonth();
        
        while ($billingMonth <= $maxDate) {
            $dueDate = $billingMonth->copy();
            $periodFrom = $billingMonth->copy()->subMonth();
            $periodTo = $billingMonth->copy()->subDay();
            
            // Check if this period is already covered by existing payments
            $isAlreadyPaid = $customer->payments()
                ->confirmed()
                ->where(function ($query) use ($periodFrom, $periodTo) {
                    $query->where(function ($q) use ($periodFrom, $periodTo) {
                        // Payment period overlaps with notice period
                        $q->where('period_from', '<=', $periodTo)
                          ->where('period_to', '>=', $periodFrom);
                    });
                })
                ->exists();
            
            if (!$isAlreadyPaid) {
                PaymentNotice::create([
                    'customer_id' => $customer->id,
                    'plan_id' => $customer->plan_id,
                    'due_date' => $dueDate,
                    'period_from' => $periodFrom,
                    'period_to' => $periodTo,
                    'amount_due' => $customer->plan->monthly_rate,
                    'status' => $dueDate < $currentDate ? 'overdue' : 'pending',
                ]);
                
                $noticesCreated++;
            }
            
            $billingMonth->addMonth();
            
            // Safety check to prevent infinite loop
            if ($billingMonth->year > $currentDate->year + 2) {
                break;
            }
        }

        return $noticesCreated;
    }

    /**
     * Bulk update payment notices for multiple customers after installation date changes.
     * Useful for administrative operations.
     */
    public function bulkUpdateNoticesForInstallationDateChanges(array $customerIds = null): array
    {
        $query = Customer::whereNotNull('plan_installed_at')
            ->whereHas('plan')
            ->with('plan');
        
        if ($customerIds) {
            $query->whereIn('id', $customerIds);
        }
        
        $customers = $query->get();
        $results = [
            'customers_processed' => 0,
            'total_notices_created' => 0,
            'errors' => []
        ];
        
        foreach ($customers as $customer) {
            try {
                $noticesCreated = $this->updateNoticesForInstallationDateChange($customer);
                $results['customers_processed']++;
                $results['total_notices_created'] += $noticesCreated;
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->full_name,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }

    /**
     * Clear payment notices based on criteria.
     */
    public function clearPaymentNotices(array $criteria = []): array
    {
        $query = PaymentNotice::query();
        
        // Apply filters based on criteria
        if (isset($criteria['status'])) {
            $query->where('status', $criteria['status']);
        }
        
        if (isset($criteria['customer_id'])) {
            $query->where('customer_id', $criteria['customer_id']);
        }
        
        if (isset($criteria['customer_ids'])) {
            $query->whereIn('customer_id', $criteria['customer_ids']);
        }
        
        if (isset($criteria['before_date'])) {
            $query->where('due_date', '<', $criteria['before_date']);
        }
        
        if (isset($criteria['after_date'])) {
            $query->where('due_date', '>', $criteria['after_date']);
        }
        
        // Get count before deletion
        $totalCount = $query->count();
        
        if ($totalCount === 0) {
            return [
                'deleted_count' => 0,
                'message' => 'No payment notices found matching the criteria.'
            ];
        }
        
        // Perform deletion
        $deletedCount = $query->delete();
        
        return [
            'deleted_count' => $deletedCount,
            'message' => "Successfully deleted {$deletedCount} payment notices."
        ];
    }

    /**
     * Clear all unpaid notices for a customer (pending and overdue).
     */
    public function clearUnpaidNoticesForCustomer(Customer $customer): int
    {
        return $customer->paymentNotices()
            ->whereIn('status', ['pending', 'overdue'])
            ->delete();
    }

    /**
     * Clear notices older than a specific date.
     */
    public function clearNoticesOlderThan(Carbon $date, array $statuses = ['pending', 'overdue']): int
    {
        return PaymentNotice::where('due_date', '<', $date)
            ->whereIn('status', $statuses)
            ->delete();
    }

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(): array
    {
        $overdueNotices = PaymentNotice::overdue()->count();
        $dueThisWeek = PaymentNotice::dueWithin(7)->count();
        $totalUnpaid = PaymentNotice::whereIn('status', ['pending', 'overdue'])->sum('amount_due');
        $totalCollected = CustomerPayment::confirmed()->whereMonth('payment_date', Carbon::now())->sum('amount');
        
        // Count customers with unpaid months
        $customersWithUnpaid = Customer::whereNotNull('plan_installed_at')
            ->where('plan_status', 'active')
            ->get()
            ->filter(function ($customer) {
                return $customer->getUnpaidMonths() > 0;
            })
            ->count();
        
        // Total pending notices
        $pendingNotices = PaymentNotice::where('status', 'pending')->count();
        
        // Average monthly collection (last 3 months)
        $avgMonthlyCollection = CustomerPayment::confirmed()
            ->where('payment_date', '>=', Carbon::now()->subMonths(3))
            ->avg('amount') ?? 0;

        return [
            'overdue_notices' => $overdueNotices,
            'due_this_week' => $dueThisWeek,
            'total_unpaid' => $totalUnpaid,
            'monthly_collected' => $totalCollected,
            'customers_with_unpaid' => $customersWithUnpaid,
            'pending_notices' => $pendingNotices,
            'avg_monthly_collection' => $avgMonthlyCollection,
        ];
    }
}
