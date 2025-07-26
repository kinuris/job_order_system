<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PaymentNotice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'plan_id',
        'due_date',
        'period_from',
        'period_to',
        'amount_due',
        'status',
        'paid_at',
        'days_overdue',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'period_from' => 'date',
        'period_to' => 'date',
        'paid_at' => 'date',
        'amount_due' => 'decimal:2',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'overdue' => 'Overdue',
        'cancelled' => 'Cancelled',
    ];

    /**
     * Get the customer that owns the notice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the plan for the notice.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get formatted amount due with currency symbol.
     */
    public function getFormattedAmountDueAttribute()
    {
        return '₱' . number_format($this->amount_due, 2);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Check if the notice is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date < Carbon::now() && $this->status === 'pending';
    }

    /**
     * Get the number of days overdue.
     */
    public function getDaysOverdueAttribute()
    {
        if ($this->status !== 'pending' || $this->due_date >= Carbon::now()) {
            return 0;
        }
        
        return Carbon::now()->diffInDays($this->due_date);
    }

    /**
     * Update overdue status and days.
     */
    public function updateOverdueStatus()
    {
        if ($this->is_overdue) {
            $this->update([
                'status' => 'overdue',
                'days_overdue' => $this->days_overdue,
            ]);
        }
    }

    /**
     * Mark as paid.
     */
    public function markAsPaid($paidDate = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => $paidDate ?: Carbon::now(),
            'days_overdue' => 0,
        ]);
    }

    /**
     * Scope for overdue notices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function ($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', Carbon::now());
                    });
    }

    /**
     * Scope for pending notices.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for notices due within X days.
     */
    public function scopeDueWithin($query, $days)
    {
        return $query->where('due_date', '<=', Carbon::now()->addDays($days))
                    ->where('status', 'pending');
    }
}
