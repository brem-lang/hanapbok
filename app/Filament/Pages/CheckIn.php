<?php

namespace App\Filament\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Charge;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CheckIn extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.check-in';

    protected static ?string $title = 'Checkin & Checkout';

    protected static ?string $navigationGroup = 'Settlement';

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Booking::query()->with('bookingItems.item')->where('resort_id', auth()->user()?->AdminResort?->id)->where('status', 'confirmed')->latest())
            ->columns([
                TextColumn::make('user.name')
                    ->label('Guest Name')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Check-In Date')
                    ->date('F d, Y h:i A')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('date_to')
                    ->label('Check-Out Date')
                    ->date('F d, Y h:i A')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('name')
                    ->form([
                        TextInput::make('name')
                            ->label('Name')
                            ->maxLength(255),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['name'],
                                fn (Builder $query, $name): Builder => $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$name.'%'))
                            );
                    }),
                // SelectFilter::make('type')
                //     ->label('Booking Type')
                //     ->options([
                //         'online' => 'Online',
                //         'walkin_booking' => 'Walk-in',
                //     ]),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn ($record) => BookingResource::getUrl('view', ['record' => $record->id]))
                    ->openUrlInNewTab(),
                Action::make('check_in')
                    ->icon('heroicon-o-check-circle')
                    ->label('Check In')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->is_checkin == 0)
                    ->action(function ($record) {

                        foreach ($record->bookingItems as $bookingItem) {
                            if ($bookingItem->item) {
                                $bookingItem->item->update(['is_occupied' => true]);
                            }
                        }

                        $record->update(['is_checkin' => true]);

                        Notification::make()
                            ->success()
                            ->title('Check In')
                            ->send();
                    }),
                Action::make('check_out')
                    ->icon('heroicon-o-check-circle')
                    ->label('Check Out')
                    ->color('warning')
                    ->modalWidth('3xl')
                    // ->requiresConfirmation()
                    ->visible(fn ($record) => $record->is_checkin == 1)
                    ->form(function ($record) {
                        return [
                            TextInput::make('total_amount')
                                ->label('Total Amount')
                                ->prefix('₱ ')
                                ->disabled()
                                ->formatStateUsing(function ($record) {
                                    return $record->amount_to_pay;
                                }),
                            TextInput::make('amount_paid')
                                ->label('Amount Paid')
                                ->prefix('₱ ')
                                ->disabled()
                                ->formatStateUsing(function ($record) {
                                    return $record->amount_paid;
                                }),
                            TextInput::make('balance')
                                ->label('Balance')
                                ->prefix('₱ ')
                                ->disabled()
                                ->formatStateUsing(function ($record) {
                                    $chargesAmount = 0;
                                    foreach ($record['additional_charges'] ?? [] as $charge) {
                                        $chargesAmount += $charge['amount'];
                                    }

                                    return $record->balance + $chargesAmount;
                                }),

                            Repeater::make('charges')
                                ->formatStateUsing(fn ($record) => $record->additional_charges)
                                ->label('Additional Charges')
                                ->reorderable(false)
                                ->schema([
                                    Select::make('name')
                                        ->disabled()
                                        ->options(Charge::pluck('name', 'id')),
                                    TextInput::make('amount')->numeric()->required(),
                                ])
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->columns(2),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'paid' => 'Paid',
                                    'pending' => 'Not Paid',
                                ]),
                        ];
                    })
                    ->action(function ($record, $data) {

                        if ($data['status'] == 'paid') {
                            foreach ($record->bookingItems as $bookingItem) {
                                if ($bookingItem->item) {
                                    $bookingItem->item->update(['is_occupied' => false]);
                                }
                            }
                            $record->balance = 0;
                            $record->amount_paid = $record->amount_to_pay;
                            $record->status = 'completed';
                            $record->is_checkin = false;
                            $record->is_review = true;
                            $record->save();

                            Notification::make()
                                ->success()
                                ->title('Check Out')
                                ->send();
                        }

                        Notification::make()
                            ->success()
                            ->title('Settle the balance first.')
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
            ]);
    }
}
