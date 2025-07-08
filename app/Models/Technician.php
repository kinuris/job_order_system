<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Technician extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'phone_number',
    ];
    
    /**
     * Get the user associated with the technician.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job orders assigned to this technician.
     */
    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class);
    }

    /**
     * Get active job orders for this technician.
     */
    public function activeJobOrders()
    {
        return $this->hasMany(JobOrder::class)
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Get the technician's full name from the associated user.
     */
    public function getFullNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown';
    }
}
