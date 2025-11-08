<?php

namespace App\Livewire;

use App\Models\Accommodation;
use App\Models\Item;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class Items extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function render()
    {
        return view('livewire.items');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Item::query()->where('resort_id', auth()->user()->AdminResort->id)->latest())
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tour Type')
                    ->formatStateUsing(fn ($state) => $state === 'night_tour' ? 'Night Tour' : 'Day Tour')
                    ->searchable(),
                TextColumn::make('accommodations.name')
                    ->label('Accommodation Type')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->searchable(),
                TextColumn::make('price')
                    ->money('PHP', true)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->heading('Accommodations')
            ->headerActions([
                Action::make('create')
                    ->label('New Accommodation ')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->hint('leave blank if not applicable')
                            ->label('Tour Type')
                            ->options([
                                'day_tour' => 'Day Tour',
                                'night_tour' => 'Night Tour',
                            ]),
                        Select::make('room_cottage_type')
                            ->label('Accommodation Type')
                            ->options(fn () => Accommodation::pluck('name', 'id')) // closure for lazy load
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('New Accommodation Type')
                                    ->required(),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                // Save the new accommodation and return its ID
                                $accommodation = Accommodation::create([
                                    'name' => $data['name'],
                                ]);

                                return $accommodation->id;
                            }),
                        TextInput::make('price')
                            ->prefix('₱')
                            ->required()
                            ->numeric()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->maxLength(255),
                        Repeater::make('otherInfo')
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('info'),
                            ]),
                        FileUpload::make('image')
                            ->openable()
                            ->label('Image')
                            ->required()
                            ->maxSize(1024)
                            ->disk('public_uploads_accommodations')
                            ->directory('/')
                            ->image()
                            ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                    ])
                    ->action(function ($data) {
                        Item::create([
                            'name' => $data['name'],
                            'type' => $data['type'],
                            'room_cottage_type' => $data['room_cottage_type'],
                            'price' => $data['price'],
                            'resort_id' => auth()->user()->AdminResort->id,
                            'image' => $data['image'],
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Entrance Fee Added')
                            ->icon('heroicon-o-check-circle')
                            ->send();
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->hint('leave blank if not applicable')
                            ->label('Tour Type')
                            ->options([
                                'day_tour' => 'Day Tour',
                                'night_tour' => 'Night Tour',
                            ]),
                        Select::make('room_cottage_type')
                            ->label('Accommodation Type')
                            ->options(fn () => Accommodation::pluck('name', 'id')) // closure for lazy load
                            ->searchable()
                            ->required(),
                        TextInput::make('price')
                            ->prefix('₱')
                            ->required()
                            ->numeric()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->maxLength(255),
                        Repeater::make('otherInfo')
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('info'),
                            ]),
                        FileUpload::make('image')
                            ->openable()
                            ->label('Image')
                            ->required()
                            ->maxSize(1024)
                            ->disk('public_uploads_accommodations')
                            ->directory('/')
                            ->image()
                            ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                    ]),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
