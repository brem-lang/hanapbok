<?php

namespace App\Livewire;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReservationStatOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $numberTourist = Booking::query()
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('resort_id', auth()->user()?->AdminResort?->id)
            ->sum('actual_check_guest');
        $pendingBooking = Booking::query()->where('status', 'pending')->where('resort_id', auth()->user()?->AdminResort?->id)->count();

        return [
            Stat::make('Number of Guest', $numberTourist),
            Stat::make('Number of Bookings', $pendingBooking),
        ];
    }
}
