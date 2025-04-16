<?php

namespace App\Filament\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Dashboard extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $userValidateIDData = [];

    public $record;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public function mount()
    {
        $this->record = auth()->user();

        $this->userValidateIDForm->fill([
            'front_id' => $this->record->front_id,
            'back_id' => $this->record->back_id,
        ]);
    }

    protected function getForms(): array
    {
        return [
            'userValidateIDForm',
        ];
    }

    public function userValidateIDForm(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->schema([
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
                ->columns(2),
        ])
            ->statePath('userValidateIDData');
    }

    public function confirmID()
    {
        $data = $this->userValidateIDForm->getState();

        auth()->user()->update([
            'front_id' => $data['front_id'],
            'back_id' => $data['back_id'],
            'notes' => null,
            'status' => 'pending',
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
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->url(fn () => UserResource::getUrl('edit', ['record' => auth()->user()->id])),
            ])
            ->sendToDatabase(User::where('role', 'admin')->get());

        redirect('/app/dashboard');
    }
}
