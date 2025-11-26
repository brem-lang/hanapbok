<?php

namespace App\Filament\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Charge;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
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

    public $overallTotal = 0;

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
            ])
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
                    // ->requiresConfirmation()
                    ->form([
                        TextInput::make('total_guest')
                            ->label('Expected Total Guest')
                            ->numeric()
                            ->formatStateUsing(function ($record) {
                                return $record->bookingItems()
                                    ->whereNotNull('entrance_fee_id')
                                    ->sum('quantity');
                            })
                            ->readOnly()
                            ->maxLength(255),
                        TextInput::make('number_guests')
                            ->label('Number of Guests')
                            ->numeric()
                            ->maxLength(255),
                    ])
                    ->visible(fn ($record) => $record->is_checkin == 0)
                    ->action(function ($record, $data) {

                        foreach ($record->bookingItems as $bookingItem) {
                            if ($bookingItem->item) {
                                $bookingItem->item->update(['is_occupied' => true]);
                            }
                        }

                        $record->update(
                            [
                                'is_checkin' => true,
                                'actual_check_in' => now(),
                                'actual_check_guest' => $data['number_guests'],
                            ]
                        );

                        Notification::make()
                            ->success()
                            ->title('Check In')
                            ->send();
                    }),
                Action::make('check_out')
                    ->icon('heroicon-o-check-circle')
                    ->label('Check Out')
                    ->color('warning')
                    ->modalWidth('4xl')
                    // ->requiresConfirmation()
                    ->visible(fn ($record) => $record->is_checkin == 1)
                    ->form(function ($record) {
                        return [
                            TextInput::make('amount_paid')
                                ->label('Down Payment')
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

                                    return number_format($record->balance + $chargesAmount, 2);
                                }),
                            Repeater::make('charges')
                                ->formatStateUsing(fn ($record) => $record->additional_charges)
                                ->label('Additional Charges')
                                ->reorderable(false)
                                ->live()
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    // Recalculate total when any repeater item is changed
                                    $total = 0;

                                    foreach ($get('charges') ?? [] as $item) {
                                        $qty = (float) ($item['quantity'] ?? 0);
                                        $amt = (float) ($item['amount'] ?? 0);
                                        $total += $qty * $amt;
                                    }

                                    // Include main amount_paid
                                    $amountPaid = (float) $get('amount_paid') ?: 0;

                                    $set('total_amount', $total + $amountPaid);
                                })
                                ->schema([
                                    Select::make('charge_id')
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->required()
                                        ->label('Charge Name')
                                        ->options(Charge::pluck('name', 'id'))
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, ?string $state, Get $get) {
                                            $charge = Charge::find($state);
                                            $set('amount', $charge?->amount);

                                            $quantity = (float) $get('quantity') ?: 0;
                                            $amount = (float) $charge?->amount ?: 0;
                                            $set('total_charges', $quantity * $amount);
                                        })
                                        ->searchable(),

                                    TextInput::make('amount')
                                        ->label('Amount')
                                        ->prefix('PHP')
                                        ->numeric()
                                        ->readOnly(),

                                    TextInput::make('quantity')
                                        ->label('Quantity')
                                        ->live()
                                        ->numeric()
                                        ->minValue(1)
                                        ->afterStateUpdated(function (Set $set, ?string $state, Get $get) {
                                            $quantity = (float) $state;
                                            $amount = (float) $get('amount') ?: 0;
                                            $set('total_charges', $quantity * $amount);
                                        }),

                                    TextInput::make('total_charges')
                                        ->label('Total Charges')
                                        ->prefix('PHP')
                                        ->numeric()
                                        ->readOnly()
                                        ->dehydrateStateUsing(function (Get $get) {
                                            $quantity = (float) $get('quantity') ?: 0;
                                            $amount = (float) $get('amount') ?: 0;

                                            return $quantity * $amount;
                                        }),
                                ])
                                ->columns(4),

                            TextInput::make('total_amount')
                                ->label('Total Amount')
                                ->prefix('PHP')
                                ->default(function (Get $get) {
                                    return $get('amount_paid');
                                })
                                ->numeric(),

                            Toggle::make('status')
                                ->label('Paid')
                                ->default('pending')
                                ->inline(),
                        ];
                    })
                    ->action(function ($record, $data) {
                        if ($data['status']) {
                            foreach ($record->bookingItems as $bookingItem) {
                                if ($bookingItem->item) {
                                    $bookingItem->item->update(['is_occupied' => false]);
                                }
                            }
                            $record->balance = 0;
                            $record->amount_paid = $data['total_amount'] + $record->amount_paid;
                            // $record->amount_to_pay = $data['total_amount'] + $record->amount_paid;
                            $record->status = 'completed';
                            $record->is_checkin = false;
                            $record->is_review = true;
                            $record->is_partial = false;
                            $record->additional_charges = $data['charges'];
                            $record->actual_check_out = now();
                            $record->save();

                            Notification::make()
                                ->success()
                                ->title('Check Out')
                                ->send();

                            Notification::make()
                                ->success()
                                ->title('New Additional Charges have been added.')
                                ->actions([
                                    ActionsAction::make('view')
                                        ->label('View Booking')
                                        ->url(fn () => route('view-booking', ['id' => $record->id]))
                                        ->openUrlInNewTab(),
                                ])
                                ->sendToDatabase(User::where('id', $record->user_id)->get());
                        } else {
                            Notification::make()
                                ->success()
                                ->title('Settle the balance first.')
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                //
            ]);
    }
}
