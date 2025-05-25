<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GuestReview extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.guest-review';

    protected static ?string $navigationGroup = 'Reports';
}
