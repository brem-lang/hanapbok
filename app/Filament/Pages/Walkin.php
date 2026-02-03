<?php

namespace App\Filament\Pages;

use App\Models\Booking as ModelsBooking;
use App\Models\BookingItem;
use App\Models\EntranceFee;
use App\Models\Item;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

use function Symfony\Component\Clock\now;

class Walkin extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.walkin';

    protected static ?string $title = 'Walk-in Guests';

    protected static ?string $navigationGroup = 'Bookings';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'list_of_person' => [
                [
                    'type' => '',
                    'quantity' => '',
                ],
            ],

            'accommodation' => [
                [
                    'type_accommodation' => '',
                    'quantity_accommodation' => '',
                ],
            ],
        ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema(
                        [
                            Section::make('List of Person')
                                ->schema([
                                    Repeater::make('list_of_person')
                                        ->label('')
                                        ->schema([
                                            Select::make('entrance_fee_id')
                                                ->label('-')
                                                ->required()
                                                ->preload()
                                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                ->searchable()
                                                ->options(
                                                    EntranceFee::where('resort_id', auth()->user()->AdminResort?->id)->get()->pluck('name', 'id')
                                                ),
                                            TextInput::make('quantity')
                                                ->required()
                                                ->numeric()
                                                ->minValue(1),
                                        ])
                                        ->columns(2),
                                ]),
                        ]
                    )
                    ->columnSpan(2),

                Group::make()
                    ->schema(
                        [
                            Section::make('Accommodation')
                                ->schema([
                                    Repeater::make('accommodation')
                                        ->label('')
                                        ->schema([
                                            Select::make('cottage_id')
                                                ->required()
                                                ->preload()
                                                ->label('-')
                                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                ->searchable()
                                                ->options(
                                                    Item::where('resort_id', auth()->user()->AdminResort?->id)->where('is_occupied', 0)->get()->pluck('name', 'id')
                                                ),
                                            TextInput::make('quantity_accommodation')
                                                ->required()
                                                ->label('Quantity')
                                                ->numeric()
                                                ->minValue(1),
                                        ])
                                        ->columns(2),
                                ]),
                        ]
                    )->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make()->columns(4)
                            ->schema([
                                DatePicker::make('date_from')
                                    ->minDate(now()->format('Y-m-d'))
                                    ->required(),
                                DatePicker::make('date_to')
                                    ->minDate(now()->format('Y-m-d'))
                                    ->required(),
                                Select::make('payment_type')
                                    ->label('Payment Type')
                                    ->required()
                                    ->options([
                                        'gcash' => 'Gcash',
                                        'cash' => 'Cash',
                                    ]),
                                TextInput::make('contact_number')
                                    ->required(),

                            ]),
                    ])
                    ->columnSpan(4),
            ])
            ->columns(4)
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();
        $entranceFeeAmount = 0;
        $accomodationAmount = 0;

        $entranceFeeIds = collect($data['list_of_person'])->pluck('entrance_fee_id')->unique()->toArray();
        $availableEntranceFees = EntranceFee::whereIn('id', $entranceFeeIds)->get()->keyBy('id');

        foreach ($data['list_of_person'] as $feeSelection) {
            $entranceFeeId = $feeSelection['entrance_fee_id'];
            $quantity = $feeSelection['quantity'];

            if (isset($availableEntranceFees[$entranceFeeId])) {
                $entranceFeeAmount += $availableEntranceFees[$entranceFeeId]->price * $quantity;
            }
        }

        $accomodationIds = collect($data['accommodation'])->pluck('cottage_id')->unique()->toArray();
        $availableAccommodations = Item::whereIn('id', $accomodationIds)->get()->keyBy('id');

        foreach ($data['accommodation'] as $accomodationSelection) {
            $accomodationId = $accomodationSelection['cottage_id'];
            $quantity = $accomodationSelection['quantity_accommodation'];

            if (isset($availableAccommodations[$accomodationId])) {
                $accomodationAmount += $availableAccommodations[$accomodationId]->price * $quantity;
            }
        }

        $total_amount = $entranceFeeAmount + $accomodationAmount;

        $book = ModelsBooking::create([
            'user_id' => auth()->user()->id,
            'resort_id' => auth()->user()->AdminResort->id,
            'status' => 'pending',
            'amount_to_pay' => $total_amount * $this->dayCount(),
            'date' => $data['date_from'],
            'date_to' => $data['date_to'],
            'payment_type' => $data['payment_type'],
            'contact_number' => $data['contact_number'],
        ]);

        foreach ($data['list_of_person'] as $feeSelection) {
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

        foreach ($data['accommodation'] as $accomodationSelection) {
            $accomodationId = $accomodationSelection['cottage_id'];
            $quantity = $accomodationSelection['quantity_accommodation'];

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

        Notification::make()
            ->title('Walk-in Booking Created')
            ->success()
            ->send();

        return redirect('app/bookings/'.$book->id);
    }

    public function dayCount()
    {
        $date = $this->data['date_from'];
        $date_to = $this->data['date_to'];

        $diff = Carbon::parse($date)->diffInDays(Carbon::parse($date_to)) + 1;

        return $diff;
    }
}
