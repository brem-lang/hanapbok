<?php

namespace App\Filament\Pages;

use App\Models\Booking as ModelsBooking;
use Filament\Pages\Page;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.calendar';

    protected static ?string $navigationGroup = 'Settlement';

    public $calendarEvents;

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public function mount()
    {
        if (auth()->user()->isGuest()) {
            abort(404);
        }

        $this->calendarEvents = ModelsBooking::query()
            ->get()
            ->map(
                fn (ModelsBooking $booking) => [
                    'id' => $booking->id,
                    'title' => 'Booking for '.$booking->user->name,
                    'start' => $booking->date,
                    'end' => $booking->date_to,
                    'backgroundColor' => $booking->is_paid ? '#10B981' : '#F59E0B',
                ]
            )
            ->toArray();
    }
}
