<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Charge;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

use function Symfony\Component\Clock\now;

class ViewBookings extends Page
{
    protected static string $resource = BookingResource::class;

    protected static string $view = 'filament.resources.booking-resource.pages.view-bookings';

    public $record;

    public ?array $formData = [];

    public function mount(Booking $record): void
    {
        $this->form->fill([
            'proof_of_payment' => $record->proof_of_payment,
            'is_partial' => $record->is_partial,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('additional_charges')
            //     ->label('Charges')
            //     ->icon('heroicon-o-plus-circle')
            //     ->form([
            // Repeater::make('charges')
            //     ->formatStateUsing(fn () => $this->record->additional_charges)
            //     ->label('Additional Charges')
            //     ->reorderable(false)
            //     ->schema([
            //         Select::make('name')
            //             ->disableOptionsWhenSelectedInSiblingRepeaterItems()
            //             ->required()
            //             ->label('Charge Name')
            //             ->options(Charge::pluck('name', 'id'))
            //             ->live()
            //             ->afterStateUpdated(function (Set $set, ?string $state) {
            //                 $charge = Charge::find($state);
            //                 $set('amount', $charge?->amount);
            //             })
            //             ->searchable(),

            //         TextInput::make('amount')
            //             ->label('Amount')
            //             ->prefix('PHP')
            //             ->numeric()
            //             ->readOnly(),
            //     ])
            //     ->columns(2),
            //     ])->action(function ($data) {

            //         $this->record->additional_charges = $data['charges'];
            //         $this->record->save();

            //         Notification::make()
            //             ->success()
            //             ->title('Charges Updated')
            //             ->icon('heroicon-o-check-circle')
            //             ->send();
            //     })
            //     ->visible(fn () => $this->record->is_checkin === 1),
            Action::make('reschedule')
                ->icon('heroicon-o-calendar')
                ->label('Reschedule')
                ->requiresConfirmation()
                ->hidden($this->record->status == 'confirmed' || $this->record->status == 'completed')
                ->action(function ($data) {
                    $dateFrom = $data['date'];
                    $dateTo = $data['date_to'];

                    // 1️⃣ Check conflicting pending bookings
                    $hasConflict = Booking::where('resort_id', $this->record->resort_id)
                        ->where('status', 'pending')
                        ->where('id', '!=', $this->record->id) // exclude self
                        ->where(function ($q) use ($dateFrom, $dateTo) {
                            $q->where('date', '<', $dateTo)
                                ->where('date_to', '>', $dateFrom);
                        })
                        ->exists();

                    // 2️⃣ If conflict → notify & stop
                    if ($hasConflict) {
                        Notification::make()
                            ->title('Date already reserved')
                            ->body('The selected date range is already reserved. Please choose another date.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $this->record->update([
                        'date' => $dateFrom,
                        'date_to' => $dateTo,
                    ]);

                    Notification::make()
                        ->title('Booking rescheduled')
                        ->success()
                        ->send();

                    Notification::make()
                        ->success()
                        ->title('Booking Rescheduled')
                        ->icon('heroicon-o-check-circle')
                        ->actions([
                            ActionsAction::make('view')
                                ->label('View')
                                ->url(fn () => route('view-booking', ['id' => $this->record->id]))
                                ->markAsRead(),
                        ])
                        ->sendToDatabase(User::where('id', $this->record->user_id)->get());
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
                ]),
            Action::make('cancel')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->label('Cancel')
                ->hidden($this->record->status == 'confirmed' || $this->record->status == 'completed')
                ->requiresConfirmation()
                ->action(function ($data) {

                    $this->record->status = 'cancelled';
                    $this->record->cancel_reason = $data['reason'];

                    $this->record->save();

                    Notification::make()
                        ->success()
                        ->title('Booking Cancelled')
                        ->icon('heroicon-o-check-circle')
                        ->send();

                    Notification::make()
                        ->success()
                        ->title('Booking Cancelled')
                        ->icon('heroicon-o-check-circle')
                        ->actions([
                            ActionsAction::make('view')
                                ->label('View')
                                ->url(fn () => route('view-booking', ['id' => $this->record->id]))
                                ->markAsRead(),
                        ])
                        ->sendToDatabase(User::where('id', $this->record->user_id)->get());

                    redirect(BookingResource::getUrl('view', ['record' => $this->record->id]));
                })
                ->form([
                    Textarea::make('reason')
                        ->label('Reason')
                        ->required(),
                ]),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('amount_paid')
                    ->numeric()
                    ->required()
                    ->hidden($this->record->status == 'confirmed'),
                Toggle::make('is_partial')
                    ->label('Partial Payment')
                    ->hidden($this->record->status == 'confirmed'),
                FileUpload::make('proof_of_payment')
                    ->disabled()
                    ->dehydrated(false)
                    ->openable()
                    ->columnSpanFull()
                    ->label('Proof of Payment')
                    ->required(function () {
                        if ($this->record->user_id == auth()->user()->id) {
                            return false;
                        } else {
                            return true;
                        }
                    })
                    ->disk('public_uploads_payments')
                    ->directory('/')
                    ->hint('Kindly upload your GCash payment proof.'),
            ])
            ->columns(2)
            ->statePath('formData');
    }

    public function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                TextEntry::make('user.name'),
                TextEntry::make('user.contact_number')
                    ->label('Contact Number'),
                TextEntry::make('status')
                    ->label('')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'primary',
                        'moved' => 'warning',
                    })
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'pending' => 'Pending',
                            'confirmed' => 'Confirm',
                            'cancelled' => 'Cancel',
                            'moved' => 'Move',
                            'completed' => 'Completed',
                            default => 'Unknown',
                        };
                    }),
                TextEntry::make('date')->dateTime('F j, Y')->label('Date From'),
                TextEntry::make('date_to')->dateTime('F j, Y')->label('Date To'),
                TextEntry::make('amount_to_pay')->label('Amount')->prefix('₱ ')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2)),
                TextEntry::make('amount_paid')->label('Amount Paid')->prefix('₱ '),
                TextEntry::make('balance')->label('Balance')->prefix('₱ '),
                TextEntry::make('resort.name')->label('Resort'),
                TextEntry::make('payment_type')->label('Payment Type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextEntry::make('reference_number')
                    ->label('Reference Number'),
                TextEntry::make('amount_send')
                    ->label('Amount Sent')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 2))
                    ->prefix('₱ '),
                // TextEntry::make('bookingItems'),
            ])
            ->columns(3);
    }

    public function confirm()
    {
        $data = $this->form->getState();

        $this->record->status = 'confirmed';
        $this->record->is_partial = $data['is_partial'];
        $this->record->amount_paid = $data['amount_paid'];
        $this->record->balance = $this->record->amount_to_pay - $data['amount_paid'];

        $this->record->save();

        Notification::make()
            ->success()
            ->title('Booking Confirmed')
            ->icon('heroicon-o-check-circle')
            ->send();

        Notification::make()
            ->success()
            ->title('Booking Confirmed')
            ->icon('heroicon-o-check-circle')
            ->actions([
                ActionsAction::make('view')
                    ->label('View')
                    ->url(fn () => route('view-booking', ['id' => $this->record->id]))
                    ->markAsRead(),
            ])
            ->sendToDatabase(User::where('id', $this->record->user_id)->get());

        redirect(BookingResource::getUrl('view', ['record' => $this->record->id]));
    }
}
