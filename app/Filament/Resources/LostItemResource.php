<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LostItemResource\Pages;
use App\Mail\LostAndFoundMail;
use App\Models\LostItem;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class LostItemResource extends Resource
{
    protected static ?string $model = LostItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Lost and Found Items';

    protected static ?string $title = 'Lost and Found Items';

    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ComponentsSection::make()
                    ->schema([
                        TextInput::make('description'),
                        DatePicker::make('date'),
                        TextInput::make('location'),
                        Select::make('type')
                            ->options([
                                'lost_item' => 'Lost Item',
                                'found_item' => 'Found Item',
                            ]),
                        FileUpload::make('photo')
                            ->disk('public_uploads_lost_item'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->columns([
                ImageColumn::make('photo')
                    ->disk('public_uploads_lost_item')
                    ->label('Photo')
                    ->sortable(),
                TextColumn::make('resort.name'),
                TextColumn::make('description')->searchable(),
                TextColumn::make('location')->searchable(),
                TextColumn::make('date')
                    ->dateTime('F j, Y h:i A')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'lost_item' => 'gray',
                        'found_item' => 'success',
                    })
                    ->formatStateUsing(fn ($state) => $state == 'lost_item' ? 'Lost Item' : 'Found Item'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => [
                        'found' => 'Found',
                        'not_found' => 'Not Found',
                        'claimed' => 'Claimed',
                        'not_claimed' => 'Not Claimed',
                    ][$state] ?? ucfirst(str_replace('_', ' ', (string) $state)))
                    ->color(fn (?string $state) => match ($state) {
                        'found' => 'success',   // green
                        'not_found' => 'danger',    // red
                        'claimed' => 'primary',   // blue
                        'not_claimed' => 'secondary', // gray
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'lost_item' => 'Lost Item',
                        'found_item' => 'Found Item',
                    ]),
            ])
            ->actions([
                Action::make('email_front_desk')
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
                            ->formatStateUsing(fn ($record) => $record->status)->required(),
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
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLostItems::route('/'),
            'create' => Pages\CreateLostItem::route('/create'),
            'edit' => Pages\EditLostItem::route('/{record}/edit'),
        ];
    }
}
