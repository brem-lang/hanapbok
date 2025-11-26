<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resort extends Model
{
    protected $guarded = [];

    protected $casts = [
        'others' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function entranceFees(): HasMany
    {
        return $this->hasMany(EntranceFee::class);
    }

    public function userAdmin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(GuestReview::class);
    }
}
