<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'monthly_rate',
        'speed_mbps',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monthly_rate' => 'decimal:2',
        'speed_mbps' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * The available plan types.
     */
    const TYPES = [
        'internet' => 'Internet',
        'cable' => 'Cable TV',
        'phone' => 'Phone',
        'bundle' => 'Bundle Package',
    ];

    /**
     * Get the customers for this plan.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Scope a query to only include active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by plan type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopeInPriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('monthly_rate', '>=', $min);
        }
        
        if ($max !== null) {
            $query->where('monthly_rate', '<=', $max);
        }
        
        return $query;
    }

    /**
     * Get the formatted monthly rate.
     */
    public function getFormattedMonthlyRateAttribute(): string
    {
        return '₱' . number_format($this->monthly_rate, 2);
    }

    /**
     * Get the plan type label.
     */
    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }
}
