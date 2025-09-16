<?php

namespace App\Livewire;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyBookings extends Component
{
    public $record;

    public $notifications;

    public $unreadNotificationsCount;

    public function mount()
    {
        $this->record = Booking::with('resort')->where('user_id', auth()->user()->id)->latest()->get();

        if (Auth::check()) {
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->route('index');
    }

    public function clearAll()
    {
        auth()->user()->notifications()->delete();

        return redirect()->route('index');
    }

    public function loadNotifications()
    {
        $this->notifications = auth()->user()
            ->notifications()
            ->take(50)
            ->get();

        $this->unreadNotificationsCount = auth()->user()->unreadNotifications->count();
    }

    public function render()
    {
        return view('livewire.my-bookings');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::find($id);

        $booking->status = 'cancelled';

        $booking->save();

        return redirect('/my-bookings');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
