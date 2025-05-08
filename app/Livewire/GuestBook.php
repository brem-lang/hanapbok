<?php

namespace App\Livewire;

use App\Models\Resort;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GuestBook extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $resorts;

    public function render()
    {
        $this->resorts = Resort::where('is_active', true)->get();

        return view('livewire.guest-book');
    }

    public function bookResort($id)
    {
        return redirect()->route('view-resort', $id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->query(User::query()->latest())
            ->columns([
                // ImageColumn::make('id_images')
                //     ->square()
                //     ->disk('public')
                //     ->label('ID'),
                // TextColumn::make('name')
                //     ->searchable()
                //     ->formatStateUsing(fn ($state) => ucfirst($state)),
                // TextColumn::make('relationship')
                //     ->searchable()
                //     ->formatStateUsing(fn ($state) => ucfirst($state)),
            ])
            ->filters([
                // ...
            ])
            ->actions([])
            ->bulkActions([
                // ...
            ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
