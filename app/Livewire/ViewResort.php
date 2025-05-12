<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\EntranceFee;
use App\Models\Item;
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

    public $items = [
        ['entrance_fee_id' => '', 'quantity' => 1],
    ];

    public $cottageRooms = [
        ['cottage_id' => '', 'quantity' => 1],
    ];

    public $record;

    public $entranceFees;

    public $resort;

    public $activePage = 'view';

    public function mount($id)
    {
        $this->record = Resort::with('items', 'entranceFees')->find($id);

        $this->entranceFees = EntranceFee::where('resort_id', $this->record->id)->get();

        $this->resort = Item::where('resort_id', $this->record->id)->get();
    }

    public function addItem()
    {
        if (! empty($this->items)) {
            $lastIndex = count($this->items) - 1;
            $this->resetValidation("items.{$lastIndex}.entrance_fee_id");
        }

        if (! empty($this->items) && empty(end($this->items)['entrance_fee_id'])) {
            $lastIndex = count($this->items) - 1;
            $this->addError("items.{$lastIndex}.entrance_fee_id", 'Please select a destination before adding another item.');

            return;
        }

        $this->items[] = ['entrance_fee_id' => '', 'quantity' => 1];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function addResortItem()
    {
        if (! empty($this->cottageRooms)) {
            $lastIndex = count($this->cottageRooms) - 1;
            $this->resetValidation("cottageRooms.{$lastIndex}.cottage_id");
        }

        if (! empty($this->cottageRooms) && empty(end($this->cottageRooms)['cottage_id'])) {
            $lastIndex = count($this->cottageRooms) - 1;
            $this->addError("cottageRooms.{$lastIndex}.cottage_id", 'Please select a cottage before adding another item.');

            return;
        }

        $this->cottageRooms[] = ['cottage_id' => '', 'quantity' => 1];
    }

    public function removeResortItem($index)
    {
        unset($this->cottageRooms[$index]);
        $this->cottageRooms = array_values($this->cottageRooms);
    }

    public function render()
    {
        return view('livewire.view-resort');
    }

    public function book()
    {
        $user = auth()->user();

        if (! $user->is_validated) {
            return redirect('/validate');
        }

        $this->activePage = 'booking';
    }

    public function viewInformation()
    {
        $this->activePage = 'view';
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name'),
        ])
            ->statePath('data');
    }

    public function submit()
    {
        $entranceFeesData = $this->items ?? [];
        $accomodationData = $this->cottageRooms ?? [];

        $entranceFeeAmount = 0;
        $accomodationAmount = 0;

        if (! empty($this->items)) {
            $lastIndex = count($this->items) - 1;
            $this->resetValidation("items.{$lastIndex}.entrance_fee_id");
        }

        if (! empty($this->items) && empty(end($this->items)['entrance_fee_id'])) {
            $lastIndex = count($this->items) - 1;
            $this->addError("items.{$lastIndex}.entrance_fee_id", 'Please select a destination before adding another item.');

            return;
        }

        // if (! empty($this->cottageRooms)) {
        //     $lastIndex = count($this->cottageRooms) - 1;
        //     $this->resetValidation("cottageRooms.{$lastIndex}.cottage_id");
        // }

        // if (! empty($this->cottageRooms) && empty(end($this->cottageRooms)['cottage_id'])) {
        //     $lastIndex = count($this->cottageRooms) - 1;
        //     $this->addError("cottageRooms.{$lastIndex}.cottage_id", 'Please select a cottage before adding another item.');

        //     return;
        // }

        $entranceFeeIds = collect($entranceFeesData)->pluck('entrance_fee_id')->unique()->toArray();
        $availableEntranceFees = EntranceFee::whereIn('id', $entranceFeeIds)->get()->keyBy('id');

        foreach ($entranceFeesData as $feeSelection) {
            $entranceFeeId = $feeSelection['entrance_fee_id'];
            $quantity = $feeSelection['quantity'];

            if (isset($availableEntranceFees[$entranceFeeId])) {
                $entranceFeeAmount += $availableEntranceFees[$entranceFeeId]->price * $quantity;
            }
        }

        $accomodationIds = collect($accomodationData)->pluck('cottage_id')->unique()->toArray();
        $availableAccommodations = Item::whereIn('id', $accomodationIds)->get()->keyBy('id');

        foreach ($accomodationData as $accomodationSelection) {
            $accomodationId = $accomodationSelection['cottage_id'];
            $quantity = $accomodationSelection['quantity'];

            if (isset($availableAccommodations[$accomodationId])) {
                $accomodationAmount += $availableAccommodations[$accomodationId]->price * $quantity;
            }
        }

        $book = Booking::create([
            'user_id' => auth()->user()->id,
            'resort_id' => $this->record->id,
            'status' => 'pending',
            'amount_to_pay' => $entranceFeeAmount + $accomodationAmount,
        ]);

        foreach ($entranceFeesData as $feeSelection) {
            $entranceFeeId = $feeSelection['entrance_fee_id'];
            $quantity = $feeSelection['quantity'];

            if (isset($availableEntranceFees[$entranceFeeId])) {
                BookingItem::create([
                    'booking_id' => $book->id,
                    'item_id' => null,
                    'entrance_fee_id' => $entranceFeeId,
                    'amount' => $availableEntranceFees[$entranceFeeId]->price * $quantity,
                    'quantity' => $quantity,
                ]);
            }
        }

        foreach ($accomodationData as $accomodationSelection) {
            $accomodationId = $accomodationSelection['cottage_id'];
            $quantity = $accomodationSelection['quantity'];

            if (isset($availableAccommodations[$accomodationId])) {
                BookingItem::create([
                    'booking_id' => $book->id,
                    'item_id' => $accomodationId,
                    'entrance_fee_id' => null,
                    'amount' => $availableAccommodations[$accomodationId]->price * $quantity,
                    'quantity' => $quantity,
                ]);
            }
        }

        return redirect('/view-booking/'.$book->id);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
