<?php

namespace App\Filament\Resources\LostItemResource\Pages;

use App\Filament\Resources\LostItemResource;
use App\Livewire\StatsLostItemsOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLostItems extends ListRecords
{
    protected static string $resource = LostItemResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            StatsLostItemsOverview::class,
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Report'),
        ];
    }
}
