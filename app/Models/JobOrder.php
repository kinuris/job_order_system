<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobOrder extends Model
{
    /** @use HasFactory<\Database\Factories\JobOrderFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'technician_id',
        'type',
        'status',
        'priority',
        'description',
        'resolution_notes',
        'scheduled_at',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * The available job order types.
     */
    const TYPES = [
        'installation' => 'Installation',
        'repair' => 'Repair',
        'upgrade' => 'Upgrade',
        'disconnection' => 'Disconnection',
        'maintenance' => 'Maintenance',
    ];

    /**
     * The available job order statuses.
     */
    const STATUSES = [
        'pending_dispatch' => 'Pending Dispatch',
        'scheduled' => 'Scheduled',
        'en_route' => 'En Route',
        'in_progress' => 'In Progress',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    /**
     * The available priority levels.
     */
    const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    /**
     * Get the customer that owns the job order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the technician assigned to the job order.
     */
    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    /**
     * Get the user assigned to this job order (through technician relationship).
     */
    public function assignedUser()
    {
        return $this->hasOneThrough(User::class, Technician::class, 'id', 'id', 'technician_id', 'user_id');
    }

    /**
     * Get the messages for this job order.
     */
    public function messages()
    {
        return $this->hasMany(JobOrderMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the unread messages for this job order.
     */
    public function unreadMessages()
    {
        return $this->hasMany(JobOrderMessage::class)->where('is_read', false);
    }

    /**
     * Scope a query to only include pending job orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending_dispatch');
    }

    /**
     * Scope a query to only include completed job orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Check if the job order is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the job order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
