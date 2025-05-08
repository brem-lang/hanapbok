<?php

namespace App\Livewire;

use App\Models\Resort;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewResort extends Component
{
    public $record;

    public function mount($id)
    {
        $this->record = Resort::with('items')->find($id);
    }

    public function render()
    {
        return view('livewire.view-resort');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
