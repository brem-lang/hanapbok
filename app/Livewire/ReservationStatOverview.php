<?php

namespace App\Livewire;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReservationStatOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $numberTourist = Booking::query()->where('status', 'confirmed')->count();
        $pendingBooking = Booking::query()->where('status', 'pending')->count();

        return [
            Stat::make('Number of Tourist', $numberTourist),
            Stat::make('Number of Bookings', $pendingBooking),
        ];
    }
}
