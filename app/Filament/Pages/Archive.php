<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Archive extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.archive';

    protected static ?string $navigationGroup = 'Documents';

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }
}
