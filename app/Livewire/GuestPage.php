<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GuestPage extends Component
{
    public function render()
    {
        return view('livewire.guest-page');
    }

    public function login()
    {
        return redirect()->to('/app/login');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
