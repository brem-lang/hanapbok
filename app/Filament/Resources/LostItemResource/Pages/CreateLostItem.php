<?php

namespace App\Filament\Resources\LostItemResource\Pages;

use App\Filament\Resources\LostItemResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLostItem extends CreateRecord
{
    protected static string $resource = LostItemResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = null;
        $data['resort_id'] = auth()->user()->AdminResort->id;
        $data['status'] = $data['type'] == 'lost_item' ? 'not_found' : 'not_claimed';

        return parent::handleRecordCreation($data);
    }
}
