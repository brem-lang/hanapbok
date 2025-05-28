<?php

namespace App\Livewire;

use App\Models\Item;
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
                TextColumn::make('room_cottage_type')
                    ->label('Accommodation Type')
                    ->formatStateUsing(fn ($state) => $state === 'cottage' ? 'Cottage' : 'Room')
                    ->searchable(),
                TextColumn::make('price')
                    ->money('PHP', true)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('New Rooms and Cottages')
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
                            ->required()
                            ->label('Accommodation Type')
                            ->options([
                                'room' => 'Room',
                                'cottage' => 'Cottage',
                            ]),
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
                    ])
                    ->action(function ($data) {
                        Item::create([
                            'name' => $data['name'],
                            'type' => $data['type'],
                            'room_cottage_type' => $data['room_cottage_type'],
                            'price' => $data['price'],
                            'resort_id' => auth()->user()->AdminResort->id,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Entrance Fee Added')
                            ->icon('heroicon-o-check-circle')
                            ->send();
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
