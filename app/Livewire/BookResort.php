<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\EntranceFee;
use App\Models\Item;
use App\Models\Resort;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class BookResort extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $record;

    public function mount(Resort $resort)
    {
        $this->record = $resort;

        // dd($this->record);
    }

    public $items = [
        ['name' => '', 'value' => ''], // Initial item
    ];

    public function addItem()
    {
        $this->items[] = ['name' => '', 'value' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Re-index the array
    }

    public function render()
    {
        return view('livewire.book-resort');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('List')
                ->columnSpan(2)
                ->schema([
                    Repeater::make('entranceFees')
                        ->label('')
                        ->schema([
                            Select::make('entranceFees')
                                ->label('Type')
                                ->searchable()
                                ->required()
                                ->options(EntranceFee::where('resort_id', $this->record->id)->get()->mapWithKeys(function ($item) {
                                    return [$item->id => $item->name.' - '.$item->type.' - ₱'.$item->price];
                                })),
                            TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->required(),
                        ])
                        ->addActionLabel('Add List')
                        ->reorderable(false)
                        ->columns(2),
                ]),

            Section::make('Accomodations')
                ->columnSpan(2)
                ->schema([
                    Repeater::make('accomodation')
                        ->label('')
                        ->schema([
                            Select::make('accomodation')
                                ->label('Type')
                                ->required()
                                ->searchable()
                                ->options(Item::where('resort_id', $this->record->id)->get()->mapWithKeys(function ($item) {
                                    return [$item->id => $item->name.' - '.$item->room_cottage_type.' - ₱'.$item->price];
                                })),
                            TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->required(),
                        ])
                        ->addActionLabel('Add List')
                        ->reorderable(false)
                        ->columns(2),
                ]),
        ])->columns(4)
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        $entranceFeesData = $data['entranceFees'] ?? [];
        $accomodationData = $data['accomodation'] ?? [];

        $entranceFeeAmount = 0;
        $accomodationAmount = 0;

        $entranceFeeIds = collect($entranceFeesData)->pluck('entranceFees')->unique()->toArray();
        $availableEntranceFees = EntranceFee::whereIn('id', $entranceFeeIds)->get()->keyBy('id');

        foreach ($entranceFeesData as $feeSelection) {
            $entranceFeeId = $feeSelection['entranceFees'];
            $quantity = $feeSelection['quantity'];

            if (isset($availableEntranceFees[$entranceFeeId])) {
                $entranceFeeAmount += $availableEntranceFees[$entranceFeeId]->price * $quantity;
            }
        }

        $accomodationIds = collect($accomodationData)->pluck('accomodation')->unique()->toArray();
        $availableAccommodations = Item::whereIn('id', $accomodationIds)->get()->keyBy('id');

        foreach ($accomodationData as $accomodationSelection) {
            $accomodationId = $accomodationSelection['accomodation'];
            $quantity = $accomodationSelection['quantity'];

            if (isset($availableAccommodations[$accomodationId])) {
                $accomodationAmount += $availableAccommodations[$accomodationId]->price * $quantity;
            }
        }

        Booking::create([
            'user_id' => auth()->user()->id,
            'resort_id' => $this->record->id,
            'status' => 'pending',
            'amount_to_pay' => $entranceFeeAmount + $accomodationAmount,
        ]);
    }

    public function cancel()
    {
        return redirect('view-resort/'.$this->record->id);
    }
}
