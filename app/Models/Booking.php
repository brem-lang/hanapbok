<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'additional_charges' => 'array',
    ];

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

    /**
     * Whether another pending booking at the resort overlaps [dateFrom, dateTo], excluding a booking id (e.g. self).
     */
    public static function hasPendingRangeOverlap(
        int $resortId,
        string $dateFrom,
        string $dateTo,
        ?int $excludeBookingId = null
    ): bool {
        return static::query()
            ->where('resort_id', $resortId)
            ->where('status', 'pending')
            ->when($excludeBookingId !== null, fn ($q) => $q->where('id', '!=', $excludeBookingId))
            ->where(function ($q) use ($dateFrom, $dateTo) {
                $q->where('date', '<', $dateTo)
                    ->where('date_to', '>', $dateFrom);
            })
            ->exists();
    }
}
