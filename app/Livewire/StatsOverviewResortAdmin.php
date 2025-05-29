<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\LostItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewResortAdmin extends BaseWidget
{
    protected function getStats(): array
    {
        $booking = Booking::query()->where('status', 'confirmed')->where('resort_id', auth()->user()?->AdminResort?->id)->count();
        $lostItem = LostItem::query()->where('status', 'not_found')->where('resort_id', auth()->user()?->AdminResort?->id)->count();
        $pendingBooking = Booking::query()->where('status', 'pending')->where('resort_id', auth()->user()?->AdminResort?->id)->count();

        return [
            Stat::make('Number of Tourist', $booking),
            Stat::make('Number of Bookings', $pendingBooking),
            Stat::make('Number of Lost Items', $lostItem),
        ];
    }
}
