<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResortResource\Pages;
use App\Filament\Resources\ResortResource\RelationManagers\EntranceFeesRelationManager;
use App\Filament\Resources\ResortResource\RelationManagers\ItemsRelationManager;
use App\Models\Resort;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResortResource extends Resource
{
    protected static ?string $model = Resort::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Resorts Management';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Resort Name')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->required(),
                                FileUpload::make('image')
                                    ->openable()
                                    ->columnSpanFull()
                                    ->label('Image')
                                    ->placeholder('You can drag and drop files here to add them.')
                                    ->required()
                                    ->maxSize(1024)
                                    ->disk('public_uploads_resorts')
                                    ->directory('/')
                                    ->image()
                                    ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->inline(false),
                                TextInput::make('barangay')
                                    ->label('Complete Address')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('resort_admin')
                                    ->formatStateUsing(fn ($record) => $record?->userAdmin->name ?? null)
                                    // ->readOnly()
                                    ->required()
                                    ->label('Resort Admin Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('resort_admin_email')
                                    ->label('Resort Admin Email')
                                    ->required()
                                    // ->readOnly()
                                    ->formatStateUsing(fn ($record) => $record?->userAdmin->email ?? null)
                                    ->maxLength(255),
                                Repeater::make('others')
                                    ->columnSpanFull()
                                    ->label('Other Details')
                                    ->schema([
                                        Textarea::make('name')->required(),
                                    ])
                                    ->columns(1),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->columns([
                // ImageColumn::make('image')
                //     ->square()
                //     ->disk('public_uploads_resorts'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('barangay')->searchable(),
                TextColumn::make('description')->searchable()->limit(20),
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
            // EntranceFeesRelationManager::class,
            // ItemsRelationManager::class,
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
