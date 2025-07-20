<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

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
            Action::make('edit-status')
                ->icon('heroicon-o-pencil')
                ->label('Edit Booking')
                ->requiresConfirmation()
                ->action(function ($data) {

                    $this->record->status = $data['status'];

                    $this->record->is_partial = $data['is_partial'];

                    $this->record->save();

                    Notification::make()
                        ->success()
                        ->title('Booking Updated')
                        ->icon('heroicon-o-check-circle')
                        ->send();

                    redirect(BookingResource::getUrl('view', ['record' => $this->record->id]));
                })
                ->form([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'cancelled' => 'Cancelled',
                            'moved' => 'Moved',
                        ])
                        ->default('pending')
                        ->required(),
                    Toggle::make('is_partial')
                        ->label('Partial Payment'),
                ]),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('amount_paid')
                    ->numeric()
                    ->required(),
                Toggle::make('is_partial')
                    ->label('Partial Payment'),
                FileUpload::make('proof_of_payment')
                    ->dehydrated(false)
                    ->openable()
                    ->columnSpanFull()
                    ->label('Proof of Payment')
                    ->required()
                    ->disk('public_uploads_payments')
                    ->directory('/')
                    ->hint('Please upload the proof of payment for gcash.'),
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
                        'moved' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => __(ucfirst($state))),
                TextEntry::make('date')->dateTime('F j, Y')->label('Date From'),
                TextEntry::make('date_to')->dateTime('F j, Y')->label('Date To'),
                TextEntry::make('amount_to_pay')->label('Amount')->prefix('₱ '),
                TextEntry::make('amount_paid')->label('Amount Paid')->prefix('₱ '),
                TextEntry::make('balance')->label('Balance')->prefix('₱ '),
                TextEntry::make('resort.name')->label('Resort'),
                TextEntry::make('payment_type')->label('Payment Type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
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

        redirect(BookingResource::getUrl('view', ['record' => $this->record->id]));
    }
}
