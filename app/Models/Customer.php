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
     * The available plan statuses.
     */
    const PLAN_STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
    ];

    /**
     * Get the full name of the customer.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

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

        if ($lastPayment) {
            // If there's a payment, next billing is after the last paid period
            return $lastPayment->period_to->addDay();
        }

        // If no payments, billing starts from installation date
        return $this->plan_installed_at->addMonth();
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
}
