<?php

namespace App\Filament\Resources\ResortResource\Pages;

use App\Filament\Resources\ResortResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResort extends CreateRecord
{
    protected static string $resource = ResortResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
