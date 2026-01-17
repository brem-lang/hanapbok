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
        $numberTourist = Booking::query()->whereIn('status', ['confirmed', 'completed'])->sum('actual_check_guest');
        $totalBookingsToday = Booking::query()
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereDate('created_at', today())
            ->count();
        // $lostItem = LostItem::query()->where('status', 'not_found')->count();

        return [
            Stat::make('Number of Guest', $numberTourist),
            Stat::make('Total Bookings Today', $totalBookingsToday),
            // Stat::make('Number of Lost Items', $lostItem),
        ];
    }
}
