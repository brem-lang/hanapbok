<?php

namespace App\Livewire;

use App\Filament\Resources\UserResource;
use App\Models\Resort;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ValidationPage extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $userValidateIDData = [];

    public $resort;

    public function mount(Request $request)
    {
        $this->resort = Resort::where('id', session('resort_id'))->with('userAdmin')->first();

        if (auth()->user()->is_validated) {
            abort(403);
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('front_id')
                ->hint('Please avoid to upload blurry images.')
                ->openable()
                ->label('Front ID')
                ->required()
                ->maxSize(1024)
                ->disk('public_uploads_id')
                ->directory('/')
                ->image()
                ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
            FileUpload::make('back_id')
                ->hint('Please avoid to upload blurry images.')
                ->label('Back ID')
                ->openable()
                ->maxSize(1024)
                ->required()
                ->disk('public_uploads_id')
                ->directory('/')
                ->image()
                ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
        ])
            ->statePath('userValidateIDData');
    }

    public function confirmID()
    {
        $data = $this->form->getState();

        auth()->user()->update([
            'front_id' => $data['front_id'],
            'back_id' => $data['back_id'],
            'notes' => null,
            'status' => 'pending',
            'resort_id' => $this->resort->id,
        ]);

        Notification::make()
            ->success()
            ->title('ID uploaded successfully')
            ->icon('heroicon-o-check-circle')
            ->send();

        Notification::make()
            ->success()
            ->title('Upload ID')
            ->icon('heroicon-o-check-circle')
            ->body(auth()->user()->name.' has uploaded ID for verification.')
            // ->actions([
            //     Action::make('view')
            //         ->label('View')
            //         ->url(fn () => UserResource::getUrl('edit', ['record' => auth()->user()->id])),
            // ])
            ->sendToDatabase(User::where('id', $this->resort->userAdmin->id)->get());

        return redirect()->route('guest-booking');
    }

    public function cancel()
    {
        return redirect()->route('guest-booking');
    }

    public function render()
    {
        return view('livewire.validation-page');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
