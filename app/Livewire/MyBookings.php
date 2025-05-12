<?php

namespace App\Livewire;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyBookings extends Component
{
    public $record;

    public function mount()
    {
        $this->record = Booking::with('resort')->where('user_id', auth()->user()->id)->latest()->get();
    }

    public function render()
    {
        return view('livewire.my-bookings');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
