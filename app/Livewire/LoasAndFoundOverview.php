<?php

namespace App\Livewire;

use App\Models\LostItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LoasAndFoundOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $lostItem = LostItem::query()->where('status', 'not_found')->where('resort_id', auth()->user()?->AdminResort?->id)->count();

        return [
            Stat::make('Number of Lost Items', $lostItem),
        ];
    }
}
