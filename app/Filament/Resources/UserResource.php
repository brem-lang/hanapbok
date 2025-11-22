<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Resorts Admin';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 3;

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
                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->hidden(fn (string $context): bool => $context === 'create'),
                        // Select::make('role')
                        //     ->label('Role')
                        //     ->options([
                        //         'guest' => 'Guest',
                        //         'resorts_admin' => 'Resorts Admin',
                        //     ])
                        //     ->required(),
                        Toggle::make('is_validated'),
                        // TextInput::make('contact_number')
                        //     ->label('Contact Number')
                        //     ->required()
                        //     ->rules(['nullable', 'regex:/^(09|\+639)\d{9}$/'])
                        //     ->maxLength(255),
                        FileUpload::make('front_id')
                            ->columnSpanFull()
                            ->hint('Please avoid to upload blurry images.')
                            ->openable()
                            ->label('Valid ID')
                            ->required()
                            ->maxSize(1024)
                            ->disk('public_uploads_id')
                            ->directory('/')
                            ->image()
                            ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                        FileUpload::make('back_id')
                            ->columnSpanFull()
                            ->hint('ex. Business permits, ID, etc.')
                            ->label('Attachments')
                            ->multiple()
                            ->openable()
                            ->maxSize(10000)
                            ->downloadable()
                            ->required()
                            ->disk('public_uploads_id')
                            ->directory('/')
                        // ->acceptedFileTypes([
                        //     // Images
                        //     'image/jpeg',
                        //     'image/png',
                        //     'image/gif',
                        //     'image/webp',
                        //     // Documents
                        //     'application/pdf',
                        //     'application/msword', // for .doc files
                        //     'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // for .docx files
                        //     'application/vnd.ms-excel', // for .xls files
                        //     'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // for .xlsx files
                        // ])
                        ,
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
                SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'guest' => 'Guest',
                        'resorts_admin' => 'Resorts Admin',
                    ]),
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                RestoreAction::make(),
                DeleteAction::make(),
                // Impersonate::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->modifyQueryUsing(function ($query) {
                return $query->where('role', 'resorts_admin')->latest();
            });
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
