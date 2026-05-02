<?php

namespace App\Filament\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

use function Symfony\Component\Clock\now;

class History extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static string $view = 'filament.pages.history';

    protected static ?string $navigationGroup = 'Bookings';

    protected static ?int $navigationSort = 40;

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
            ->query(Booking::query()->whereIn('status', ['confirmed', 'completed'])->where('resort_id', auth()->user()?->AdminResort?->id)->latest())
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('user.name')
                    ->label('Guest Name')
                    ->sortable()
                    // ->toggleable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Check-In Date')
                    ->date('F d, Y h:i A')
                    ->searchable()
                    // ->toggleable()
                    ->sortable(),
                TextColumn::make('date_to')
                    ->label('Check-Out Date')
                    ->date('F d, Y h:i A')
                    ->searchable()
                    // ->toggleable()
                    ->sortable(),
                TextColumn::make('actual_check_in')
                    ->label('Actual Check-In')
                    ->date('F d, Y h:i A')
                    ->timezone('Asia/Manila')
                    ->searchable()
                    // ->toggleable()
                    ->sortable(),
                TextColumn::make('actual_check_out')
                    ->label('Actual Check-Out')
                    ->date('F d, Y h:i A')
                    ->timezone('Asia/Manila')
                    ->searchable()
                // ->toggleable()
                ,
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Guest')
                    ->options(User::where('role', 'guest')->pluck('name', 'id'))->preload()
                    ->searchable(),
                Filter::make('createdated_at')
                    ->form([
                        DatePicker::make('date')
                            ->label('Check-In Date'),
                        DatePicker::make('date_to')
                            ->label('Check-Out Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date_to', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Action::make('change_dates')
                    ->label('Change')
                    ->icon('heroicon-o-calendar')
                    ->modalHeading('Change booking dates')
                    ->hidden(fn(Booking $record): bool => $record->status !== 'completed')
                    ->fillForm(function (Booking $record): array {
                        return [
                            'date' => $record->date ? Carbon::parse($record->date)->format('Y-m-d') : null,
                            'date_to' => $record->date_to ? Carbon::parse($record->date_to)->format('Y-m-d') : null,
                        ];
                    })
                    ->form([
                        DatePicker::make('date')
                            ->label('Date From')
                            ->required(),
                        DatePicker::make('date_to')
                            ->label('Date To')
                            ->required(),
                    ])
                    ->action(function (array $data, Booking $record): void {
                        $dateFrom = $data['date'];
                        $dateTo = $data['date_to'];

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
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn($record) => BookingResource::getUrl('view', ['record' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
