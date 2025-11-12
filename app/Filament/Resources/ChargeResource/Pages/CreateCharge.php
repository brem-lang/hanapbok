<?php

namespace App\Filament\Resources\ChargeResource\Pages;

use App\Filament\Resources\ChargeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCharge extends CreateRecord
{
    protected static string $resource = ChargeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['resort_id'] = auth()->user()->AdminResort->id;

        $created = $this->getModel()::create($data);

        return $created;
    }
}
