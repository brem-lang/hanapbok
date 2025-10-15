<?php

namespace App\Filament\Pages;

use App\Models\Resort;
use Filament\Pages\Page;

class BookingCount extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.booking-count';

    protected static ?string $title = 'Booking';

    protected static ?string $navigationGroup = 'Bookings';

    public $resorts;

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function mount()
    {
        if (auth()->user()->isGuest()) {
            abort(404);
        }

        $this->resorts = Resort::withCount('bookings')->get(['id', 'name']);
    }
}
