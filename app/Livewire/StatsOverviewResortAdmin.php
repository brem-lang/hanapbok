<?php

namespace App\Livewire;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewResortAdmin extends BaseWidget
{
    protected function getStats(): array
    {
        $booking = Booking::query()
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('resort_id', auth()->user()?->AdminResort?->id)
            ->sum('actual_check_guest');
        $numberBookings = Booking::query()->where('resort_id', auth()->user()?->AdminResort?->id)->count();
        $pendingBooking = Booking::query()->where('status', 'pending')->where('resort_id', auth()->user()?->AdminResort?->id)->count();

        return [
            Stat::make('Number of Guest', $booking),
            Stat::make('Number of Bookings', $numberBookings),
            Stat::make('Number of Pending Bookings', $pendingBooking),
        ];
    }
}
