<?php

namespace App\Filament\Resources\GuestValidationResource\Pages;

use App\Filament\Resources\GuestValidationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuestValidations extends ListRecords
{
    protected static string $resource = GuestValidationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
