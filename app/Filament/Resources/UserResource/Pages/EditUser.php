<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('reject')
            //     ->icon('heroicon-o-arrow-path')
            //     ->form([
            //         Textarea::make('notes')
            //             ->default('pls recheck uploaded id'),
            //     ])
            //     ->action(function ($data, Model $record) {
            //         $record->update(
            //             [
            //                 'notes' => $data['notes'],
            //                 'status' => 'rejected',
            //             ]
            //         );

            //         Notification::make()
            //             ->warning()
            //             ->title('Rejected')
            //             ->icon('heroicon-o-check-circle')
            //             ->body($data['notes'])
            //             ->actions([
            //                 ActionsAction::make('view')
            //                     ->label('View')
            //                     ->url('dashboard'),
            //             ])
            //             ->sendToDatabase(User::where('id', $record->id)->get());
            //     }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($data['is_validated'] == 1) {
            Notification::make()
                ->warning()
                ->title('ID Verified')
                ->icon('heroicon-o-check-circle')
                ->actions([
                    ActionsAction::make('view')
                        ->label('View')
                        ->url('dashboard'),
                ])
                ->sendToDatabase(User::where('id', $record->id)->get());
        }

        $data['notes'] = null;
        $data['status'] = 'validated';
        $data['is_validated'] = $data['is_validated'];

        $record->update($data);

        return $record;
    }
}
