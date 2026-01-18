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
}
