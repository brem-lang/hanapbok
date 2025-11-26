<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;

class ReservationStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reservation-stats';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 8;

    protected static ?string $title = 'Reservation Trends';

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('reports.revenueTrends', [
                    'resort_id' => auth()->user()?->AdminResort?->id,
                ]))
                ->openUrlInNewTab(),
        ];
    }
}
