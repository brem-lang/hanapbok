<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class GuestValidation extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.guest-validation';

    protected static ?string $navigationGroup = 'Validation';

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('role', 'guest')->where('resort_id', auth()->user()->AdminResort?->id)->latest())
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
            ->filters([])
            ->actions([
                EditAction::make()
                    ->label('Validate')
                    ->modalHeading('Validate')
                    ->requiresConfirmation()
                    ->form([
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
                            ->hint('Please avoid to upload blurry images.')
                            ->label('Attachments')
                            ->multiple()
                            ->openable()
                            ->maxSize(10000)
                            ->required()
                            ->disk('public_uploads_id')
                            ->directory('/')
                            ->acceptedFileTypes([
                                // Images
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'image/webp',
                                // Documents
                                'application/pdf',
                                'application/msword', // for .doc files
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // for .docx files
                                'application/vnd.ms-excel', // for .xls files
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // for .xlsx files
                            ]),
                        Toggle::make('is_validated'),
                    ]),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
