<?php

namespace App\Filament\Pages;

use App\Models\Resort;
use Filament\Pages\Page;

class Reservations extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reservations';

    public $resorts;

    public static function canAccess(): bool
    {
        return auth()->user()->isGuest() && auth()->user()->is_validated;
    }

    public function getResorts()
    {
        $this->resorts = Resort::get()->toArray();
    }
}
