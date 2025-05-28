<?php

namespace App\Livewire;

use App\Models\LostItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsLostItemsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $lost = LostItem::query()->where('resort_id', auth()->user()->AdminResort->id)->where('type', 'lost_item')->latest()->count();
        $found = LostItem::query()->where('resort_id', auth()->user()->AdminResort->id)->where('type', 'found_item')->latest()->count();

        return [
            Stat::make('Lost Items', $lost),
            Stat::make('Found Items', $found),
            Stat::make('Total Items', $lost + $found),
        ];
    }
}
