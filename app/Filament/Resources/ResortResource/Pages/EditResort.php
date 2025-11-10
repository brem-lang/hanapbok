<?php

namespace App\Filament\Resources\ResortResource\Pages;

use App\Filament\Resources\ResortResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditResort extends EditRecord
{
    protected static string $resource = ResortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $user = User::updateOrCreate(
            ['email' => $data['resort_admin_email']],
            [
                'name' => $data['resort_admin'],
                'role' => 'resorts_admin',
                'password' => bcrypt('password'),
                'contact_number' => $data['contact_number'],
            ]
        );

        unset($data['resort_admin']);
        unset($data['resort_admin_email']);
        unset($data['is_validated']);
        unset($data['contact_number']);

        $data['user_id'] = $user->id;

        $record->update($data);

        return $record;
    }
}
