<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\LostItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $numberTourist = Booking::query()->where('status', 'confirmed')->count();
        $pendingBooking = Booking::query()->where('status', 'pending')->count();
        // $lostItem = LostItem::query()->where('status', 'not_found')->count();

        return [
            Stat::make('Number of Tourist', $numberTourist),
            Stat::make('Number of Bookings', $pendingBooking),
            // Stat::make('Number of Lost Items', $lostItem),
        ];
    }
}
