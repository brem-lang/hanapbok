<?php

namespace App\Filament\Resources\ResortResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $modelLabel = 'Rooms and Cottages';

    protected static ?string $title = 'Accomodation';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->hint('leave blank if not applicable')
                    ->label('Tour Type')
                    ->options([
                        'day_tour' => 'Day Tour',
                        'night_tour' => 'Night Tour',
                    ]),
                Select::make('room_cottage_type')
                    ->required()
                    ->label('Accommodation Type')
                    ->options([
                        'room' => 'Room',
                        'cottage' => 'Cottage',
                    ]),
                Forms\Components\TextInput::make('price')
                    ->prefix('₱')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->maxLength(255),
                Repeater::make('otherInfo')
                    ->reorderable(false)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('info'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Rooms and Cottages')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tour Type')
                    ->formatStateUsing(fn ($state) => $state === 'night_tour' ? 'Night Tour' : 'Day Tour')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_cottage_type')
                    ->label('Accommodation Type')
                    ->formatStateUsing(fn ($state) => $state === 'cottage' ? 'Cottage' : 'Room')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('PHP', true)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
