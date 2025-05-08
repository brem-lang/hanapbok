<?php

namespace App\Livewire;

use App\Models\Resort;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewResort extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $record;

    public $activePage = 'view';

    public function mount($id)
    {
        $this->record = Resort::with('items', 'entranceFees')->find($id);
    }

    public function render()
    {
        return view('livewire.view-resort');
    }

    public function book()
    {
        $user = auth()->user();

        if (! $user->is_validated) {
            // $this->activePage = 'validation';
            return redirect('/validate');
        }

        dd('booking');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name'),
        ])
            ->statePath('data');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
