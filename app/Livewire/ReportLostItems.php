<?php

namespace App\Livewire;

use App\Models\LostItem;
use Livewire\Component;

class ReportLostItems extends Component
{
    public $record;

    public $description;

    public $location;

    public $date;

    public function render()
    {
        return view('livewire.report-lost-items');
    }

    public function report()
    {
        $lostItem = LostItem::create([
            'user_id' => auth()->user()->id,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
        ]);

        $this->dispatch('swal:modal');

        $this->description = '';
        $this->location = '';
        $this->date = '';
    }
}
