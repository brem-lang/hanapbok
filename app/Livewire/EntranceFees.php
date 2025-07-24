<?php

namespace App\Livewire;

use App\Models\EntranceFee;
use Filament\Forms\Components\Select;
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

class EntranceFees extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function render()
    {
        return view('livewire.entrance-fees');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(EntranceFee::query()->where('resort_id', auth()->user()->AdminResort->id)->latest())
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->formatStateUsing(fn ($state) => $state === 'night_tour' ? 'Night Tour' : ($state === 'day_tour' ? 'Day Tour' : 'Free'))
                    ->searchable(),
                TextColumn::make('price')
                    ->money('PHP', true)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->heading('Entrance')
            ->headerActions([
                Action::make('create')
                    ->label('New Entrance')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->required()
                            ->options([
                                'free' => 'Free',
                                'day_tour' => 'Day Tour',
                                'night_tour' => 'Night Tour',
                            ]),
                        TextInput::make('price')
                            ->prefix('₱')
                            ->required()
                            ->numeric()
                            ->maxLength(255),
                    ])
                    ->action(function ($data) {
                        EntranceFee::create([
                            'name' => $data['name'],
                            'type' => $data['type'],
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
