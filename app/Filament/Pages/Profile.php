<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class Profile extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.profile';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public ?array $data = [];

    public $user;

    // public static function canAccess(): bool
    // {
    //     return auth()->user()->isGuest() && auth()->user()->is_validated;
    // }

    public function mount()
    {
        if (auth()->user()->isGuest()) {
            abort(404);
        }
        $this->user = auth()->user();

        $auth = auth()->user();

        $this->form->fill([
            'name' => $auth->name,
            'email' => $auth->email,
            'role' => $auth->role,
            'front_id' => $auth->front_id,
            'back_id' => $auth->back_id,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->readOnly()
                            ->dehydrated(false)
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->readOnly()
                            ->dehydrated(false)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)),
                        // Select::make('role')
                        //     ->label('Role')
                        //     ->options([
                        //         'admin' => 'Admin',
                        //         'guest' => 'Guest',
                        //     ])
                        //     ->required(),
                        // FileUpload::make('front_id')
                        //     ->dehydrated(false)
                        //     ->hint('Please avoid to upload blurry images.')
                        //     ->openable()
                        //     ->label('Front ID')
                        //     ->maxSize(1024)
                        //     ->disk('public_uploads_id')
                        //     ->directory('/')
                        //     ->image()
                        //     ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                        // FileUpload::make('back_id')
                        //     ->dehydrated(false)
                        //     ->hint('Please avoid to upload blurry images.')
                        //     ->label('Back ID')
                        //     ->openable()
                        //     ->maxSize(1024)
                        //     ->disk('public_uploads_id')
                        //     ->directory('/')
                        //     ->image()
                        //     ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        $this->user->update($data);

        session()->put([
            'password_hash_'.auth()->getDefaultDriver() => $this->user->getAuthPassword(),
        ]);

        Notification::make()
            ->title('Updated')
            ->success()
            ->send();
    }
}
