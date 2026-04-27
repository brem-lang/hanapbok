<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

use function Symfony\Component\Clock\now;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Bookings';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('resort.name')
                    ->label('Resort')
                    ->searchable()
                    ->visible(auth()->user()->isAdmin())
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Date From')
                    ->dateTime('F j, Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_to')
                    ->label('Date To')
                    ->dateTime('F j, Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'pending' => 'Pending',
                            'confirmed' => 'Confirm',
                            'cancelled' => 'Cancel',
                            'moved' => 'Move',
                            default => 'Unknown',
                        };
                    })
                    ->color(
                        fn ($state) => match ($state) {
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'cancelled' => 'danger',
                            'moved' => 'warning',
                            'completed' => 'success',
                        }
                    )
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_type')
                    ->label('Payment Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(
                        fn ($state) => match ($state) {
                            'gcash' => 'success',
                            'walk_in' => 'warning',
                            'cash' => 'danger',
                        }
                    ),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('change_dates')
                    ->label('Change')
                    ->icon('heroicon-o-calendar')
                    ->modalHeading('Change booking dates')
                    ->hidden(fn (Booking $record): bool => ! in_array($record->status, ['pending', 'confirmed'], true))
                    ->fillForm(function (Booking $record): array {
                        return [
                            'date' => $record->date ? Carbon::parse($record->date)->format('Y-m-d') : null,
                            'date_to' => $record->date_to ? Carbon::parse($record->date_to)->format('Y-m-d') : null,
                        ];
                    })
                    ->form([
                        DatePicker::make('date')
                            ->label('Date From')
                            ->minDate(now()->format('Y-m-d'))
                            ->required(),
                        DatePicker::make('date_to')
                            ->label('Date To')
                            ->minDate(now()->format('Y-m-d'))
                            ->required(),
                    ])
                    ->action(function (array $data, Booking $record): void {
                        $dateFrom = $data['date'];
                        $dateTo = $data['date_to'];

                        if (Booking::hasPendingRangeOverlap(
                            $record->resort_id,
                            $dateFrom,
                            $dateTo,
                            $record->id
                        )) {
                            Notification::make()
                                ->title('Date already reserved')
                                ->body('The selected date range is already reserved. Please choose another date.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->update([
                            'date' => $dateFrom,
                            'date_to' => $dateTo,
                        ]);

                        Notification::make()
                            ->title('Booking dates updated')
                            ->success()
                            ->send();
                    }),
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->url(fn ($record) => BookingResource::getUrl('view', ['record' => $record->id])),

            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->modifyQueryUsing(function ($query) {
                $auth = auth()->user();

                if ($auth->isResortsAdmin()) {
                    return $query->whereHas('resort', function ($query) use ($auth) {
                        $query->where('user_id', $auth->id)->where('status', '!=', 'completed');
                    })->latest();
                }

                if ($auth->isAdmin()) {
                    return $query->latest();
                }
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
            'index' => Pages\ListBookings::route('/'),
            'view' => Pages\ViewBookings::route('/{record}'),
            // 'create' => Pages\CreateBooking::route('/create'),
            // 'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
