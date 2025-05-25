<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ReservationStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reservation-stats';

    protected static ?string $navigationGroup = 'Reports';
}
