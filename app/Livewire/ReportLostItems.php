<?php

namespace App\Livewire;

use App\Models\LostItem;
use App\Models\Resort;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReportLostItems extends Component
{
    use WithFileUploads;

    public $record;

    public $description;

    public $location;

    public $date;

    public $selectResort;

    public $resorts;

    public $uploadPhoto;

    public function mount()
    {
        $this->resorts = Resort::get();
    }

    public function render()
    {
        return view('livewire.report-lost-items');
    }

    public function report()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'selectResort' => 'required|exists:resorts,id',
            'uploadPhoto' => 'image|max:2048',
        ]);

        $path = $this->uploadPhoto->store('', 'public_uploads_lost_item');

        $lostItem = LostItem::create([
            'user_id' => auth()->user()->id,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
            'resort_id' => $this->selectResort,
            'photo' => $path,
        ]);

        $this->dispatch('swal:modal');

        return redirect('/lost-items');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
