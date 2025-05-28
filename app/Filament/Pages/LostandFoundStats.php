<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LostandFoundStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.lostand-found-stats';

    protected static ?string $title = 'Lost and Found Stats';

    protected static ?string $navigationGroup = 'Reports';

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }
}
