<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
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
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('proof_of_payment')
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

    public function confirm()
    {
        $this->record->status = 'confirmed';

        $this->record->save();

        Notification::make()
            ->success()
            ->title('Booking Confirmed')
            ->icon('heroicon-o-check-circle')
            ->send();

        redirect(BookingResource::getUrl('view', ['record' => $this->record->id]));
    }
}
