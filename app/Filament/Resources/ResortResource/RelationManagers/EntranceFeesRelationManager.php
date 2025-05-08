<?php

namespace App\Filament\Resources\ResortResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EntranceFeesRelationManager extends RelationManager
{
    protected static string $relationship = 'entranceFees';

    protected static ?string $modelLabel = 'Entrance';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->required()
                    ->options([
                        'free' => 'Free',
                        'day_tour' => 'Day Tour',
                        'night_tour' => 'Night Tour',
                    ]),
                TextInput::make('price')
                    ->prefix('₱')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn ($state) => $state === 'night_tour' ? 'Night Tour' : ($state === 'day_tour' ? 'Day Tour' : 'Free'))
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
