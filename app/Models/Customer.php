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
}
