<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestValidationResource\Pages;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GuestValidationResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Validation';

    // protected static ?string $title = 'Guest Validation';
    protected static ?string $modelLabel = 'Guest Validation';

    public static function canAccess(): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {

        $data = parent::getEloquentQuery()->where('role', 'guest')->where('resort_id', auth()->user()->AdminResort?->id)->latest();

        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        FileUpload::make('front_id')
                            // ->columnSpanFull()
                            ->disabled()
                            ->hint('Please avoid to upload blurry images.')
                            ->openable()
                            ->label('Front')
                            ->disk('public_uploads_id')
                            ->directory('/')
                            ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                        FileUpload::make('back_id')
                            // ->columnSpanFull()
                            ->disabled()
                            ->hint('Please avoid to upload blurry images.')
                            ->label('Back')
                            ->openable()
                            ->disk('public_uploads_id')
                            ->directory('/'),
                        Toggle::make('is_validated'),
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
                TextColumn::make('email')->searchable(),
                TextColumn::make('role')
                    ->badge()->color(fn (string $state): string => match ($state) {
                        'resorts_admin' => 'success',
                        'admin' => 'warning',
                        'guest' => 'gray',
                    })->formatStateUsing(fn (string $state): string => __(ucfirst($state)))
                    ->searchable(),
                TextColumn::make('is_validated')
                    ->label('Validated')
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
            'index' => Pages\ListGuestValidations::route('/'),
            'create' => Pages\CreateGuestValidation::route('/create'),
            'edit' => Pages\EditGuestValidation::route('/{record}/edit'),
        ];
    }
}
