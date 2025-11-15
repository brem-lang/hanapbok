<?php

namespace App\Filament\Resources\GuestValidationResource\Pages;

use App\Filament\Resources\GuestValidationResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGuestValidation extends EditRecord
{
    protected static string $resource = GuestValidationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($data['is_validated'] == 1) {
            Notification::make()
                ->success()
                ->title('Validated Successfully')
                ->icon('heroicon-o-check-circle')
                ->sendToDatabase(User::where('id', $record->id)->get());
        }

        return parent::handleRecordUpdate($record, $data);
    }
}
