<?php

namespace App\Filament\Resources\LostItemResource\Pages;

use App\Filament\Resources\LostItemResource;
use App\Mail\LostAndFoundMail;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EditLostItem extends EditRecord
{
    protected static string $resource = LostItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Action::make('mail')
                ->label('Mail')
                ->icon('heroicon-o-envelope')
                ->action(function ($data, $record) {
                    $details = [
                        'name' => $record->user->name,
                        'message' => $data['message'],
                    ];

                    Mail::to($record->user->email)->send(new LostAndFoundMail($details));

                    Notification::make()
                        ->title('Email Sent')
                        ->success()
                        ->send();
                })
                ->form([
                    Textarea::make('message')
                        ->label('Message')
                        ->required()
                        ->maxLength(500)
                        ->placeholder('Type your message here'),
                ]),
            Action::make('status')
                ->label('Update Status')
                ->icon('heroicon-o-document-text')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('remarks')
                        ->label('Remarks')
                        ->formatStateUsing(fn ($record) => $record->remarks),
                    Select::make('status')
                        ->label('Status')
                        ->options(
                            function ($record) {
                                if ($record->type == 'lost_item') {
                                    return [
                                        'found' => 'Found',
                                        'not_found' => 'Not Found',
                                    ];
                                }

                                if ($record->type == 'found_item') {
                                    return [
                                        'claimed' => 'Claimed',
                                        'not_claimed' => 'Not Claimed',
                                    ];
                                }
                            }
                        )
                        ->formatStateUsing(fn ($record) => $record->status)
                        ->required(),
                ])
                ->action(function ($record, $data) {

                    // dd($data['status']);
                    $record->status = $data['status'];
                    $record->remarks = $data['remarks'];
                    $record->save();

                    Notification::make()
                        ->title('Status Updated')
                        ->icon('heroicon-o-document-text')
                        ->body('Status updated successfully.')
                        ->success()
                        ->send();

                    Notification::make()
                        ->success()
                        ->title('Item Status Updated')
                        ->icon('heroicon-o-check-circle')
                        ->actions([
                            ActionsAction::make('view')
                                ->label('View')
                                ->url(fn () => route('view-reports', ['id' => $record->id]))
                                ->markAsRead(),
                        ])
                        ->sendToDatabase(User::where('id', $record->user_id)->get());
                }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        unset($data['description']);
        unset($data['location']);
        unset($data['date']);
        unset($data['type']);
        unset($data['photo']);

        return parent::handleRecordUpdate($record, $data);
    }
}
