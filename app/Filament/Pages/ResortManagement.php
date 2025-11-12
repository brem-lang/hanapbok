<?php

namespace App\Filament\Pages;

use App\Models\Resort;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ResortManagement extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $view = 'filament.pages.resort-management';

    protected static ?string $navigationGroup = 'Resorts Management';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public $record;

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public function mount()
    {
        if (auth()->user()->isGuest()) {
            abort(404);
        }
        $resort = Resort::where('user_id', auth()->user()->id)->first();

        $this->record = $resort;
        $this->form->fill([
            'name' => $resort?->name,
            'description' => $resort?->description,
            'image' => $resort?->image,
            'is_active' => $resort?->is_active,
            'others' => $resort?->others,
            'qr' => $resort?->qr,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->required(),
                                FileUpload::make('image')
                                    ->openable()
                                    ->label('Image')
                                    // ->required()
                                    ->maxSize(1024)
                                    ->disk('public_uploads_resorts')
                                    ->directory('/')
                                    ->image()
                                    ->rules(['nullable', 'mimes:jpg,jpeg,png', 'max:1024']),
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->inline(false),
                                Repeater::make('others')
                                    ->label('Other Details')
                                    ->schema([
                                        Textarea::make('name')->required(),
                                    ])
                                    ->columns(1),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(2),
                Group::make()
                    ->schema([
                        Section::make('QR Code')
                            ->schema([
                                FileUpload::make('qr')
                                    ->label('')
                                    ->openable()
                                    ->maxSize(1024)
                                    ->disk('public_uploads_qr')
                                    ->directory('/')
                                    ->required()
                                    ->image(),
                            ])
                            ->columns(1),
                    ]),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()
            ->success()
            ->title('Resort Updated')
            ->icon('heroicon-o-check-circle')
            ->send();

        return redirect('app/resort-management');
    }
}
