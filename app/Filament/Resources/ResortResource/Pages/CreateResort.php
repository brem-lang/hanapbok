<?php

namespace App\Filament\Resources\ResortResource\Pages;

use App\Filament\Resources\ResortResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateResort extends CreateRecord
{
    protected static string $resource = ResortResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create([
            'name' => $data['resort_admin'],
            'email' => $data['resort_admin_email'],
            'role' => 'resorts_admin',
            'password' => bcrypt('password'),
            'contact_number' => $data['contact_number'],
        ]);

        unset($data['resort_admin']);
        unset($data['resort_admin_email']);
        unset($data['is_validated']);
        unset($data['contact_number']);

        $data['user_id'] = $user->id;

        $created = $this->getModel()::create($data);

        return $created;
    }
}
