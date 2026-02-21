<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'room_id',
        'renter_id',
        'message',
        'status',
        'payment_screenshot',
        'payment_method',
        'esewa_transaction_id',
        'esewa_amount',
        'esewa_ref_id',
        'paid_at',
        'requested_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'paid_at' => 'datetime',
        'esewa_amount' => 'decimal:2',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function renter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    /**
     * Check if this booking has an active payment (paid or approved).
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['paid', 'approved']);
    }

    /**
     * Scope for bookings that lock a room (paid or approved).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['paid', 'approved']);
    }
}
