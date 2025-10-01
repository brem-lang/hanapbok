<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ViewBooking extends Component
{
    use WithFileUploads;

    public $record;

    public $payment_image;

    protected $rules = [
        'payment_image' => 'required|image|max:2048', // Required, must be an image, max 2MB
    ];

    public function mount($id)
    {
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
        ]);

        session()->flash('message', 'Payment confirmed. Your proof has been uploaded.');

        Notification::make()
            ->success()
            ->title('Payment Sent')
            ->icon('heroicon-o-check-circle')
            ->body(auth()->user()->name.' has sent a payment.')
            // ->actions([
            //     Action::make('view')
            //         ->label('View')
            //         ->url(fn () => BookingResource::getUrl('view', ['record' => $this->booking->id]))
            //         ->markAsRead(),
            // ])
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
