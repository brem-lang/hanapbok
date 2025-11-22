<?php

namespace App\Livewire;

use App\Models\LostItem;
use Livewire\Component;

class ViewReports extends Component
{
    public $record;

    public function render()
    {
        return view('livewire.view-reports');
    }

    public function mount($id)
    {
        $this->record = LostItem::where('id', $id)->first();
    }
}
