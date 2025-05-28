<?php

namespace App\Filament\Pages;

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

class LostItems extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.lost-items';

    protected static ?string $navigationGroup = 'Lost Items';

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
            ->query(LostItem::query()->where('resort_id', auth()->user()->AdminResort->id)->latest())
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
                    ->dateTime('F j, Y')
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
                    ->formatStateUsing(fn ($state) => $state == 'found' ? 'Found' : 'Not Found'),
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
                            ->options([
                                'not_found' => 'Not Found',
                                'found' => 'Found',
                            ])
                            ->formatStateUsing(fn ($record) => $record->status)
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
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
