<?php

namespace App\Filament\Pages;

use App\Mail\LostAndFoundMail;
use App\Models\LostItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class LostItems extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.lost-items';

    protected static ?string $navigationGroup = 'Lost Items';

    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public function mount()
    {
        if (auth()->user()->isGuest()) {
            abort(404);
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LostItem::query()->where('resort_id', auth()->user()?->AdminResort?->id)->latest())
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
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);

        return $table;
    }
}
