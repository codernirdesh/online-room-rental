<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'address',
        'city',
        'province',
        'rent_price',
        'room_type',
        'amenities',
        'image',
        'available_from',
        'status',
    ];

    protected $casts = [
        'rent_price' => 'decimal:2',
        'available_from' => 'date',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if the room has an active booking (paid or approved).
     */
    public function hasActiveBooking(): bool
    {
        return $this->bookings()->active()->exists();
    }

    /**
     * Check if the room is available for booking.
     */
    public function isBookable(): bool
    {
        return $this->status === 'available' && !$this->hasActiveBooking();
    }

    /**
     * Scope for rooms available for booking.
     */
    public function scopeBookable($query)
    {
        return $query->where('status', 'available')
            ->whereDoesntHave('bookings', function ($q) {
                $q->whereIn('status', ['paid', 'approved']);
            });
    }
}
