<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResortResource\Pages;
use App\Filament\Resources\ResortResource\RelationManagers\RoomsRelationManager;
use App\Models\Resort;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResortResource extends Resource
{
    protected static ?string $model = Resort::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->openable()
                            ->label('Image')
                            ->required()
                            ->maxSize(1024)
                            ->disk('public_uploads_resorts')
                            ->directory('/')
                            ->image()
                            ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                        Toggle::make('is_active')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('description')->searchable()->limit(20),
                TextColumn::make('price')->searchable()->money('PHP'),
                TextColumn::make('is_active')
                    ->label('Active')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->modifyQueryUsing(function ($query) {

                return $query->latest();
            });
    }

    public static function getRelations(): array
    {
        return [
            RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResorts::route('/'),
            'create' => Pages\CreateResort::route('/create'),
            'edit' => Pages\EditResort::route('/{record}/edit'),
        ];
    }
}
