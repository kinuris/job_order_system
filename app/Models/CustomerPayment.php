<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class CustomerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'plan_id',
        'amount',
        'plan_rate',
        'payment_date',
        'period_from',
        'period_to',
        'payment_method',
        'reference_number',
        'notes',
        'status',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'period_from' => 'date',
        'period_to' => 'date',
        'amount' => 'decimal:2',
        'plan_rate' => 'decimal:2',
    ];

    const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'gcash' => 'GCash',
        'paymaya' => 'PayMaya',
        'check' => 'Check',
        'other' => 'Other',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
    ];

    /**
     * Get the customer that owns the payment.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the plan for the payment.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get formatted amount with currency symbol.
     */
    public function getFormattedAmountAttribute()
    {
        return '₱' . number_format($this->amount, 2);
    }

    /**
     * Get formatted plan rate with currency symbol.
     */
    public function getFormattedPlanRateAttribute()
    {
        return '₱' . number_format($this->plan_rate, 2);
    }

    /**
     * Get payment method label.
     */
    public function getPaymentMethodLabelAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get the period duration in months (discrete calculation).
     */
    public function getPeriodMonthsAttribute()
    {
        // Use discrete month calculation similar to Customer::getUnpaidMonths
        $current = $this->period_from->copy()->startOfMonth();
        $end = $this->period_to->copy()->startOfMonth();
        
        $months = 0;
        while ($current <= $end) {
            $months++;
            $current->addMonth();
        }
        
        return $months;
    }

    /**
     * Get the discrete months covered by this payment.
     */
    public function getDiscreteMonthsAttribute()
    {
        $months = [];
        $current = $this->period_from->copy()->startOfMonth();
        $end = $this->period_to->copy()->startOfMonth();
        
        while ($current <= $end) {
            $months[] = $current->format('M Y');
            $current->addMonth();
        }
        
        return $months;
    }

    /**
     * Get formatted discrete months as a string.
     */
    public function getFormattedDiscreteMonthsAttribute()
    {
        $months = $this->discrete_months;
        
        if (count($months) === 1) {
            return $months[0];
        } elseif (count($months) === 2) {
            return implode(' & ', $months);
        } else {
            return $months[0] . ' - ' . end($months) . ' (' . count($months) . ' months)';
        }
    }

    /**
     * Get calculated amount based on monthly rate and months covered.
     */
    public function getCalculatedAmountAttribute()
    {
        return $this->plan_rate * $this->period_months;
    }

    /**
     * Check if this is an advance payment (covers future periods).
     */
    public function getIsAdvancePaymentAttribute()
    {
        return $this->period_to > Carbon::now();
    }

    /**
     * Scope for confirmed payments only.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for payments within a date range.
     */
    public function scopeInPeriod($query, $from, $to)
    {
        return $query->where(function ($q) use ($from, $to) {
            $q->whereBetween('period_from', [$from, $to])
              ->orWhereBetween('period_to', [$from, $to])
              ->orWhere(function ($q2) use ($from, $to) {
                  $q2->where('period_from', '<=', $from)
                     ->where('period_to', '>=', $to);
              });
        });
    }
}
