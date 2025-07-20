<?php

namespace App\Livewire;

use App\Models\Resort;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GuestBook extends Component implements HasForms
{
    use InteractsWithForms;

    public $search = '';

    public $resorts;

    public function mount()
    {
        $this->resorts = Resort::where('is_active', true)->get();
    }

    public function render()
    {
        return view('livewire.guest-book');
    }

    public function searchResorts()
    {
        $this->resorts = Resort::where('is_active', true)->when(
            $this->search,
            function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            }
        )->get();
    }

    public function bookResort($id)
    {

        if (Auth::check()) {
            return redirect()->route('view-resort', $id);
        } else {
            return redirect('/app/register');
        }

        // return redirect()->route('view-resort', $id);
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
