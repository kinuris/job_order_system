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
            $installationDay = $installationDate->day;
            
            // Start from one month after installation, maintaining the same day
            $billingDate = $this->addMonthsPreservingDay($installationDate, 1, $installationDay);
            
            // Generate notices until current month + 1 (for next month's notice)
            $maxDate = $currentDate->copy()->addMonth()->endOfMonth();
            
            while ($billingDate <= $maxDate) {
                $dueDate = $billingDate->copy();
                $periodFrom = $billingDate->copy()->subMonth();
                $periodTo = $billingDate->copy()->subDay();
                
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
                
                // Move to next month, maintaining the installation day
                $billingDate = $this->addMonthsPreservingDay($billingDate, 1, $installationDay);
                
                // Safety check to prevent infinite loop
                if ($billingDate->year > $currentDate->year + 2) {
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
     * This will maintain only one current payment notice but adjust its due date to match the new installation pattern.
     */
    public function updateNoticesForInstallationDateChange(Customer $customer): int
    {
        if (!$customer->plan_installed_at || !$customer->plan) {
            return 0;
        }

        // Delete ALL existing payment notices for this customer
        $deletedNotices = $customer->paymentNotices()->delete();

        // Calculate the next due date based on the new installation date
        $installationDate = Carbon::parse($customer->plan_installed_at);
        $installationDay = $installationDate->day;
        $currentDate = Carbon::now();
        
        // Find the next billing date that preserves the installation day
        $nextBillingDate = $installationDate->copy();
        
        // Keep adding months until we get a future date
        while ($nextBillingDate <= $currentDate) {
            $nextBillingDate = $this->addMonthsPreservingDay($nextBillingDate, 1, $installationDay);
        }
        
        // Create period for the notice (typically the month before the due date)
        $periodFrom = $nextBillingDate->copy()->subMonth()->startOfMonth();
        $periodTo = $nextBillingDate->copy()->subMonth()->endOfMonth();
        
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
        
        $noticesCreated = 0;
        
        if (!$isAlreadyPaid) {
            PaymentNotice::create([
                'customer_id' => $customer->id,
                'plan_id' => $customer->plan_id,
                'due_date' => $nextBillingDate,
                'period_from' => $periodFrom,
                'period_to' => $periodTo,
                'amount_due' => $customer->plan->monthly_rate,
                'status' => $nextBillingDate < $currentDate ? 'overdue' : 'pending',
            ]);
            
            $noticesCreated = 1;
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
     * Get payment notices with sorting options.
     */
    public function getPaymentNoticesWithSorting(array $options = [])
    {
        $sortBy = $options['sort_by'] ?? 'customer_name';
        $sortDirection = $options['sort_direction'] ?? 'asc';
        $status = $options['status'] ?? null;
        $customerSearch = $options['customer_search'] ?? null;
        $customerId = $options['customer_id'] ?? null;
        $planId = $options['plan_id'] ?? null;
        $dueDateFrom = $options['due_date_from'] ?? null;
        $dueDateTo = $options['due_date_to'] ?? null;
        $amountMin = $options['amount_min'] ?? null;
        $amountMax = $options['amount_max'] ?? null;
        $overdueOnly = $options['overdue_only'] ?? false;
        $hasNotices = $options['has_notices'] ?? false;

        // Get payment notices with customer relationship
        $query = PaymentNotice::with(['customer' => function ($query) {
            $query->with('plan');
        }, 'plan']);

        // Apply filters
        if ($status && is_array($status) && !empty($status)) {
            $query->whereIn('status', $status);
        } elseif ($status) {
            $query->where('status', $status);
        }

        if ($overdueOnly) {
            $query->where('due_date', '<', now());
        }

        if ($customerSearch) {
            $query->whereHas('customer', function ($customerQuery) use ($customerSearch) {
                $customerQuery->where(function ($q) use ($customerSearch) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$customerSearch}%"])
                      ->orWhere('email', 'LIKE', "%{$customerSearch}%")
                      ->orWhere('phone_number', 'LIKE', "%{$customerSearch}%");
                });
            });
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($planId) {
            $query->where('plan_id', $planId);
        }

        if ($dueDateFrom) {
            $query->where('due_date', '>=', $dueDateFrom);
        }

        if ($dueDateTo) {
            $query->where('due_date', '<=', $dueDateTo);
        }

        if ($amountMin) {
            $query->where('amount_due', '>=', $amountMin);
        }

        if ($amountMax) {
            $query->where('amount_due', '<=', $amountMax);
        }

        if ($hasNotices) {
            $query->whereIn('status', ['pending', 'sent']);
        }

        $notices = $query->get();

        // Sort the collection based on the criteria
        if ($sortBy === 'customer_name' || $sortBy === 'name') {
            $notices = $notices->sortBy(function ($notice) {
                return strtolower($notice->customer->full_name ?? '');
            }, SORT_REGULAR, $sortDirection === 'desc');
        } elseif ($sortBy === 'unpaid_months') {
            $notices = $notices->sortBy(function ($notice) {
                return $notice->customer->getUnpaidMonths();
            }, SORT_NUMERIC, $sortDirection === 'desc');
        } elseif ($sortBy === 'due_date') {
            $notices = $notices->sortBy('due_date', SORT_REGULAR, $sortDirection === 'desc');
        } elseif ($sortBy === 'amount') {
            $notices = $notices->sortBy('amount_due', SORT_NUMERIC, $sortDirection === 'desc');
        }

        return $notices;
    }

    /**
     * Get payment notices with sorting and additional metadata.
     * Now includes ALL customers and uses display-friendly unpaid months.
     */
    public function getPaymentNoticesWithMetadata(array $options = []): array
    {
        // First, get all customers (not just those with notices)
        $allCustomers = Customer::with(['plan', 'paymentNotices', 'payments'])
            ->whereNotNull('plan_installed_at')
            ->where('plan_status', 'active')
            ->whereHas('plan')
            ->get();

        // Create payment notices for all customers (even if they don't have actual notices)
        $noticesCollection = collect();
        $customerUnpaidCounts = [];

        foreach ($allCustomers as $customer) {
            // Get actual payment notices for this customer
            $actualNotices = $customer->paymentNotices()
                ->whereIn('status', ['pending', 'overdue'])
                ->get();

            if ($actualNotices->isNotEmpty()) {
                // Add actual notices to collection
                foreach ($actualNotices as $notice) {
                    $noticesCollection->push($notice);
                }
            } else {
                // Create a virtual notice entry for customers without actual notices
                $virtualNotice = new \stdClass();
                $virtualNotice->id = null;
                $virtualNotice->customer_id = $customer->id;
                $virtualNotice->customer = $customer;
                $virtualNotice->plan = $customer->plan;
                $virtualNotice->due_date = null;
                $virtualNotice->amount_due = 0;
                $virtualNotice->status = 'current';
                $virtualNotice->period_from = null;
                $virtualNotice->period_to = null;
                $virtualNotice->created_at = null;
                $virtualNotice->updated_at = null;
                $virtualNotice->days_overdue = 0;
                
                $noticesCollection->push($virtualNotice);
            }

            // Store the display-friendly unpaid months count
            $customerUnpaidCounts[$customer->id] = $customer->getUnpaidMonthsDisplay();
        }

        // Apply filters to the collection
        $notices = $this->applyFiltersToNoticesCollection($noticesCollection, $options);

        return [
            'notices' => $notices,
            'customer_unpaid_counts' => $customerUnpaidCounts
        ];
    }

    /**
     * Apply filters to the notices collection.
     */
    protected function applyFiltersToNoticesCollection($notices, array $options = [])
    {
        $sortBy = $options['sort_by'] ?? 'customer_name';
        $sortDirection = $options['sort_direction'] ?? 'asc';
        $status = $options['status'] ?? null;
        $customerSearch = $options['customer_search'] ?? null;
        $overdueOnly = $options['overdue_only'] ?? false;

        // Apply status filter
        if ($status && is_array($status) && !empty($status)) {
            $notices = $notices->filter(function ($notice) use ($status) {
                return in_array($notice->status ?? 'current', $status);
            });
        } elseif ($status) {
            $notices = $notices->filter(function ($notice) use ($status) {
                return ($notice->status ?? 'current') === $status;
            });
        }

        // Apply overdue filter
        if ($overdueOnly) {
            $notices = $notices->filter(function ($notice) {
                return $notice->due_date && $notice->due_date < now();
            });
        }

        // Apply customer search filter
        if ($customerSearch) {
            $notices = $notices->filter(function ($notice) use ($customerSearch) {
                $customer = $notice->customer;
                $fullName = trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''));
                return stripos($fullName, $customerSearch) !== false ||
                       stripos($customer->email ?? '', $customerSearch) !== false ||
                       stripos($customer->phone_number ?? '', $customerSearch) !== false;
            });
        }

        // Sort the collection
        if ($sortBy === 'customer_name' || $sortBy === 'name') {
            $notices = $notices->sortBy(function ($notice) {
                $customer = $notice->customer;
                return strtolower(trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')));
            }, SORT_REGULAR, $sortDirection === 'desc');
        } elseif ($sortBy === 'unpaid_months') {
            $notices = $notices->sortBy(function ($notice) {
                return $notice->customer->getUnpaidMonthsForDisplay();
            }, SORT_NUMERIC, $sortDirection === 'desc');
        } elseif ($sortBy === 'due_date') {
            $notices = $notices->sortBy(function ($notice) {
                return $notice->due_date ?? '9999-12-31'; // Put nulls at the end
            }, SORT_REGULAR, $sortDirection === 'desc');
        } elseif ($sortBy === 'amount') {
            $notices = $notices->sortBy(function ($notice) {
                return $notice->amount_due ?? 0;
            }, SORT_NUMERIC, $sortDirection === 'desc');
        }

        return $notices;
    }

    /**
     * Get customers with payment notices, sorted by name or unpaid months.
     * Now includes ALL customers regardless of payment status.
     */
    public function getCustomersWithNoticesSorted(array $options = []): \Illuminate\Database\Eloquent\Collection
    {
        $sortBy = $options['sort_by'] ?? 'name'; // 'name' or 'unpaid_months'
        $sortDirection = $options['sort_direction'] ?? 'asc';
        $hasUnpaidOnly = $options['has_unpaid_only'] ?? false;

        $customers = Customer::with(['plan', 'paymentNotices', 'payments'])
            ->whereNotNull('plan_installed_at')
            ->where('plan_status', 'active')
            ->whereHas('plan')
            ->get();

        // Filter customers with unpaid notices only if specifically requested (for backwards compatibility)
        if ($hasUnpaidOnly) {
            $customers = $customers->filter(function ($customer) {
                return $customer->getUnpaidMonths() > 0;
            });
        }

        // Sort the collection
        if ($sortBy === 'name') {
            $customers = $customers->sortBy(function ($customer) {
                return strtolower($customer->full_name);
            }, SORT_REGULAR, $sortDirection === 'desc');
        } elseif ($sortBy === 'unpaid_months') {
            $customers = $customers->sortBy(function ($customer) {
                return $customer->getUnpaidMonths();
            }, SORT_NUMERIC, $sortDirection === 'desc');
        }

        return $customers;
    }

    /**
     * Get payment notices summary with sorting and grouping options.
     * Now includes ALL customers regardless of payment status.
     */
    public function getPaymentNoticesSummary(array $options = []): array
    {
        $sortBy = $options['sort_by'] ?? 'name';
        $sortDirection = $options['sort_direction'] ?? 'asc';
        $groupBy = $options['group_by'] ?? null; // 'status', 'overdue_range', null
        $statusFilter = $options['status_filter'] ?? null;

        // Get ALL customers, not just those with unpaid notices
        $customers = $this->getCustomersWithNoticesSorted([
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'has_unpaid_only' => false // Include all customers
        ]);

        $summary = [
            'total_customers' => $customers->count(),
            'total_notices' => 0,
            'total_amount' => 0,
            'customers' => []
        ];

        foreach ($customers as $customer) {
            $unpaidNotices = $customer->paymentNotices->whereIn('status', ['pending', 'overdue']);
            
            if ($statusFilter) {
                $unpaidNotices = $unpaidNotices->where('status', $statusFilter);
            }

            $unpaidMonths = $customer->getUnpaidMonths();
            
            // Include ALL customers in the summary
            $customerData = [
                'id' => $customer->id,
                'name' => $customer->full_name,
                'unpaid_months' => $unpaidMonths,
                'notices_count' => $unpaidNotices->count(),
                'total_amount' => $unpaidNotices->sum('amount_due'),
                'oldest_notice_date' => $unpaidNotices->min('due_date'),
                'payment_status' => $this->getPaymentStatus($customer),
                'latest_payment_date' => $customer->getLatestPaymentDate(),
                'notices' => $unpaidNotices->map(function ($notice) {
                    return [
                        'id' => $notice->id,
                        'due_date' => $notice->due_date,
                        'amount_due' => $notice->amount_due,
                        'status' => $notice->status,
                        'days_overdue' => $notice->days_overdue ?? 0
                    ];
                })
            ];

            $summary['customers'][] = $customerData;
            $summary['total_notices'] += $unpaidNotices->count();
            $summary['total_amount'] += $unpaidNotices->sum('amount_due');
        }

        // Apply grouping if requested
        if ($groupBy) {
            $summary['grouped'] = $this->groupNoticesSummary($summary['customers'], $groupBy);
        }

        return $summary;
    }

    /**
     * Group notices summary by specified criteria.
     */
    protected function groupNoticesSummary(array $customers, string $groupBy): array
    {
        $grouped = [];

        foreach ($customers as $customer) {
            $groupKey = $this->getGroupKey($customer, $groupBy);
            
            if (!isset($grouped[$groupKey])) {
                $grouped[$groupKey] = [
                    'customers' => [],
                    'total_notices' => 0,
                    'total_amount' => 0,
                    'customer_count' => 0
                ];
            }

            $grouped[$groupKey]['customers'][] = $customer;
            $grouped[$groupKey]['total_notices'] += $customer['notices_count'];
            $grouped[$groupKey]['total_amount'] += $customer['total_amount'];
            $grouped[$groupKey]['customer_count']++;
        }

        return $grouped;
    }

    /**
     * Get group key for grouping notices.
     */
    protected function getGroupKey(array $customer, string $groupBy): string
    {
        switch ($groupBy) {
            case 'unpaid_months':
                $months = $customer['unpaid_months'];
                if ($months === 0) return '0 months';
                if ($months === 1) return '1 month';
                if ($months <= 3) return '2-3 months';
                if ($months <= 6) return '4-6 months';
                return '6+ months';

            case 'overdue_range':
                $oldestDate = Carbon::parse($customer['oldest_notice_date']);
                $daysOverdue = $oldestDate->diffInDays(Carbon::now());
                
                if ($daysOverdue <= 0) return 'Current';
                if ($daysOverdue <= 30) return '1-30 days overdue';
                if ($daysOverdue <= 60) return '31-60 days overdue';
                if ($daysOverdue <= 90) return '61-90 days overdue';
                return '90+ days overdue';

            case 'amount_range':
                $amount = $customer['total_amount'];
                if ($amount < 1000) return 'Under ₱1,000';
                if ($amount < 5000) return '₱1,000 - ₱5,000';
                if ($amount < 10000) return '₱5,000 - ₱10,000';
                return '₱10,000+';

            default:
                return 'Other';
        }
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

    /**
     * Add months to a date while preserving the day of month.
     * Handles edge cases like adding months to January 31st.
     */
    protected function addMonthsPreservingDay(Carbon $date, int $months, int $preferredDay): Carbon
    {
        $newDate = $date->copy()->addMonths($months);
        
        // Try to set to the preferred day, but don't exceed the month's days
        $newDate->day = min($preferredDay, $newDate->daysInMonth);
        
        return $newDate;
    }
}
