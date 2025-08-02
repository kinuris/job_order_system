<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'service_address',
        'plan_id',
        'plan_installed_at',
        'plan_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'plan_installed_at' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($customer) {
            // Check if plan_installed_at has changed and update payment notices
            if ($customer->wasChanged('plan_installed_at') && $customer->plan_installed_at) {
                $paymentService = app(\App\Services\PaymentService::class);
                $paymentService->updateNoticesForInstallationDateChange($customer);
            }
        });
    }

    /**
     * The available plan statuses.
     */
    const PLAN_STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
    ];

    /**
     * Get the customer's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Phone accessor for backward compatibility.
     */
    public function getPhoneAttribute(): string
    {
        return $this->phone_number;
    }

    /**
     * Relationships
     */

    /**
     * Get the plan for the customer.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the job orders for the customer.
     */
    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class);
    }

    /**
     * Get the payments for the customer.
     */
    public function payments()
    {
        return $this->hasMany(CustomerPayment::class);
    }

    /**
     * Get the payment notices for the customer.
     */
    public function paymentNotices()
    {
        return $this->hasMany(PaymentNotice::class);
    }

    /**
     * Get the plan status label.
     */
    public function getPlanStatusLabel(): string
    {
        return self::PLAN_STATUSES[$this->plan_status] ?? ucfirst($this->plan_status ?? '');
    }

    /**
     * Check if customer has an active plan.
     */
    public function hasActivePlan(): bool
    {
        return $this->plan_id && $this->plan_status === 'active';
    }

    /**
     * Get the customer's next billing date.
     */
    public function getNextBillingDate()
    {
        if (!$this->plan_installed_at) {
            return null;
        }

        $lastPayment = $this->payments()
            ->confirmed()
            ->orderBy('period_to', 'desc')
            ->first();

        $installationDay = $this->plan_installed_at->day;

        if ($lastPayment) {
            // If there's a payment, next billing is after the last paid period
            // Maintain the same day as installation date
            $nextBilling = $lastPayment->period_to->addDay();
            
            // Move to the next month and set to installation day
            $nextMonth = $nextBilling->copy()->addMonth();
            $nextMonth->day = min($installationDay, $nextMonth->daysInMonth);
            
            return $nextMonth;
        }

        // If no payments, billing starts one month after installation, same day
        $nextBilling = $this->plan_installed_at->copy()->addMonth();
        $nextBilling->day = min($installationDay, $nextBilling->daysInMonth);
        
        return $nextBilling;
    }

    /**
     * Get overdue payment notices.
     */
    public function getOverdueNotices()
    {
        return $this->paymentNotices()->overdue()->get();
    }

    /**
     * Get current payment balance (negative means overpaid/advance payment).
     */
    public function getPaymentBalance()
    {
        if (!$this->plan || !$this->plan_installed_at) {
            return 0;
        }

        $totalPaid = $this->payments()
            ->confirmed()
            ->sum('amount');

        $monthsActive = $this->plan_installed_at->diffInMonths(now()) + 1;
        $totalDue = $monthsActive * $this->plan->monthly_rate;

        return $totalDue - $totalPaid;
    }

    /**
     * Check if customer has advance payments.
     */
    public function hasAdvancePayments()
    {
        return $this->getPaymentBalance() < 0;
    }

    /**
     * Get the number of unpaid months for this customer (discrete calculation).
     */
    public function getUnpaidMonths(): int
    {
        if (!$this->plan_installed_at || $this->plan_installed_at > now()) {
            return 0;
        }

        // Calculate discrete months since installation
        $installationDate = $this->plan_installed_at->startOfMonth();
        $currentDate = now()->startOfMonth();
        $monthsSinceInstallation = $installationDate->diffInMonths($currentDate) + 1;

        // Calculate discrete paid months
        $payments = $this->payments()->where('status', 'confirmed')->get();
        $totalPaidMonths = 0;

        foreach ($payments as $payment) {
            $periodStart = $payment->period_from->startOfMonth();
            $periodEnd = $payment->period_to->startOfMonth();
            $monthsCovered = $periodStart->diffInMonths($periodEnd) + 1;
            $totalPaidMonths += $monthsCovered;
        }

        // Return discrete unpaid months
        return max(0, $monthsSinceInstallation - $totalPaidMonths);
    }

    /**
     * Get unpaid months for display purposes.
     * Returns negative values for advance payments to show "X over" status.
     */
    public function getUnpaidMonthsForDisplay(): int
    {
        if (!$this->plan_installed_at || $this->plan_installed_at > now()) {
            return 0;
        }

        // Calculate discrete months since installation
        $installationDate = $this->plan_installed_at->startOfMonth();
        $currentDate = now()->startOfMonth();
        $monthsSinceInstallation = $installationDate->diffInMonths($currentDate) + 1;

        // Calculate discrete paid months
        $payments = $this->payments()->where('status', 'confirmed')->get();
        $totalPaidMonths = 0;

        foreach ($payments as $payment) {
            $periodStart = $payment->period_from->startOfMonth();
            $periodEnd = $payment->period_to->startOfMonth();
            $monthsCovered = $periodStart->diffInMonths($periodEnd) + 1;
            $totalPaidMonths += $monthsCovered;
        }

        // Return the raw difference (can be negative for advance payments)
        return $monthsSinceInstallation - $totalPaidMonths;
    }

    /**
     * Get a human-readable unpaid months display string.
     */
    public function getUnpaidMonthsDisplay(): string
    {
        $unpaidMonths = $this->getUnpaidMonthsForDisplay();
        
        if ($unpaidMonths == 0) {
            return '0';
        } elseif ($unpaidMonths < 0) {
            $advanceMonths = abs($unpaidMonths);
            return $advanceMonths . ' over';
        } else {
            return (string) $unpaidMonths;
        }
    }

    /**
     * Check if the customer is fully paid (no unpaid months).
     */
    public function isFullyPaid(): bool
    {
        return $this->getUnpaidMonths() === 0;
    }

    /**
     * Get the customer's latest payment date.
     */
    public function getLatestPaymentDate()
    {
        $latestPayment = $this->payments()
            ->confirmed()
            ->orderBy('payment_date', 'desc')
            ->first();

        return $latestPayment ? $latestPayment->payment_date : null;
    }
}
