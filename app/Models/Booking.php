<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
