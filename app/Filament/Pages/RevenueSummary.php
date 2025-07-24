<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class RevenueSummary extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.revenue-summary';

    protected static ?string $navigationGroup = 'Reports';

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    // The property to hold the current filter state.
    public string $filter = 'monthly';

    // Method to change the active filter.
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        // We no longer need to dispatch an event, as Livewire will handle the re-render.
    }

    // A computed property to get chart data. It re-calculates automatically when $this->filter changes.
    #[Computed()]
    public function chartData(): array
    {
        $query = Booking::query()
            ->select(
                DB::raw('SUM(amount_paid) as revenue')
            )
            ->where('status', 'confirmed')
            ->where('resort_id', auth()->user()->resort_id);

        switch ($this->filter) {
            case 'daily':
                $data = $query
                    ->addSelect(DB::raw('DATE(created_at) as period'))
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('period')
                    ->orderBy('period', 'asc')
                    ->get();

                $labels = $data->pluck('period')->map(fn ($date) => Carbon::parse($date)->format('M d'));
                break;

            case 'yearly':
                $data = $query
                    ->addSelect(DB::raw('YEAR(created_at) as period'))
                    ->groupBy('period')
                    ->orderBy('period', 'asc')
                    ->get();

                $labels = $data->pluck('period');
                break;

            case 'monthly':
            default:
                $data = $query
                    ->addSelect(DB::raw('YEAR(created_at) as year'), DB::raw('MONTHNAME(created_at) as month_name'), DB::raw('MONTH(created_at) as month_num'))
                    ->whereYear('created_at', now()->year)
                    ->groupBy('year', 'month_name', 'month_num')
                    ->orderBy('month_num', 'asc')
                    ->get();

                $labels = $data->pluck('month_name');
                break;
        }

        return [
            'labels' => $labels,
            'revenue' => $data->pluck('revenue')->map(fn ($val) => (float) $val),
        ];
    }

    /**
     * This method passes data to the view. It replaces the need for a render() method.
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'chartData' => $this->chartData(),
        ];
    }
}
