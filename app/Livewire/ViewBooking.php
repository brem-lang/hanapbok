<?php

namespace App\Livewire;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Session;

class ViewBooking extends Component
{
    use WithFileUploads;

    public $record;

    public $payment_image;

    public $reference_number;

    public $amount_sent;

    protected $rules = [
        'payment_image' => 'required|image|max:2048', // Required, must be an image, max 2MB
        'reference_number' => 'required|string',
        'amount_sent' => 'required|numeric|min:0',
    ];

    public function mount($id)
    {
        if (! auth()->user()->isGuest()) {
            abort(404);
        }

        // if (! Session::has('user_2fa')) {
        //     abort(404);
        // }

        $booking = Booking::with(['resort.userAdmin', 'bookingItems', 'bookingItems.item', 'bookingItems.entranceFee'])->find($id);

        if (! $booking) {
            abort(404);
        }

        if ($booking->user_id != auth()->user()->id) {
            abort(404);
        }

        $this->record = $booking;
    }

    public function render()
    {
        return view('livewire.view-booking');
    }

    public function confirm()
    {
        $this->validate();

        $path = $this->payment_image->store('', 'public_uploads_payments');

        $this->record->update([
            'proof_of_payment' => $path,
            'reference_number' => $this->reference_number,
            'amount_send' => number_format($this->amount_sent, 2, '.', ''),
        ]);

        session()->flash('message', 'Payment confirmed. Your proof has been uploaded.');

        Notification::make()
            ->success()
            ->title('New Booking and Payment Received')
            ->icon('heroicon-o-check-circle')
            ->body('A new booking has been created by '.auth()->user()->name)
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->url(fn () => BookingResource::getUrl('view', ['record' => $this->record->id]))
                    ->markAsRead(),
            ])
            ->sendToDatabase(User::where('id', $this->record->resort->userAdmin->id)->get());

        // Optionally reset the property
        return redirect('view-booking/'.$this->record->id);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
