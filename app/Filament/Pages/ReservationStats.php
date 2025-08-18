<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ReservationStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reservation-stats';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 8;

    protected static ?string $title = 'Reservation Status';

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }
}
