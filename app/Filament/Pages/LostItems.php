<?php

namespace App\Filament\Pages;

use App\Models\LostItem;
use Filament\Pages\Page;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class LostItems extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.lost-items';

    protected static ?string $navigationGroup = 'Lost Items';

    public function mount()
    {
        if (auth()->user()->isGuest()) {
            abort(404);
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LostItem::query()->latest())
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
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);

        return $table;
    }
}
