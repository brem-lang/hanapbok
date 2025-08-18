<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class Dashboard extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $userValidateIDData = [];

    public $record;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    // The property to hold the current filter state.
    public string $filter = 'monthly';

    // Method to change the active filter.
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    // A computed property to get chart data. It re-calculates automatically when $this->filter changes.
    #[Computed()]
    public function chartData(): array
    {
        $datasets = [];
        $labels = [];
        $user = Auth::user();

        $query = Booking::query()->where('status', 'confirmed');

        // ** FIX: Use the correct relationship to get the resort ID **
        if ($user->isResortsAdmin()) {
            // Get the resort ID from the AdminResort relationship.
            $resortId = $user->AdminResort?->id;
            // Apply the filter only if the resort ID exists.
            if ($resortId) {
                $query->where('resort_id', $resortId);
            } else {
                // If the resort admin has no associated resort, show no data.
                $query->whereRaw('1 = 0');
            }
        } elseif (! $user->isAdmin()) {
            // If the user is neither a super admin nor a resort admin, show no data.
            $query->whereRaw('1 = 0');
        }
        // Note: If the user is an admin, no resort filter is applied, so they see all data.

        switch ($this->filter) {
            case 'daily':
                $startDate = now()->subDays(4)->startOfDay();
                $endDate = now()->endOfDay();

                $dailyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_bookings'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'asc')
                    ->pluck('total_bookings', 'date');

                $bookingsByDay = [];
                for ($i = 0; $i < 5; $i++) {
                    $date = now()->subDays(4 - $i)->format('Y-m-d');
                    $labels[] = Carbon::parse($date)->format('M d');
                    $bookingsByDay[] = $dailyData->get($date, 0);
                }

                $datasets = [['label' => 'Daily Bookings', 'data' => $bookingsByDay]];
                break;

            case 'yearly':
                $startYear = 2021;
                $endYear = now()->year;
                $labels = range($startYear, $endYear);

                $yearlyData = (clone $query)
                    ->whereBetween(DB::raw('YEAR(created_at)'), [$startYear, $endYear])
                    ->select(DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as total_bookings'))
                    ->groupBy(DB::raw('YEAR(created_at)'))
                    ->orderBy('year', 'asc')
                    ->pluck('total_bookings', 'year');

                $bookingsByYear = [];
                foreach ($labels as $year) {
                    $bookingsByYear[] = $yearlyData->get($year, 0);
                }

                $datasets = [['label' => 'Yearly Bookings', 'data' => $bookingsByYear]];
                break;

            case 'monthly':
            default:
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                $monthlyData = (clone $query)
                    ->whereYear('created_at', now()->year)
                    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_bookings'))
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->orderBy('month', 'asc')
                    ->pluck('total_bookings', 'month');

                $bookingsByMonth = array_fill(1, 12, 0);
                foreach ($monthlyData as $month => $bookings) {
                    $bookingsByMonth[$month] = $bookings;
                }

                $datasets = [['label' => 'Monthly Bookings', 'data' => array_values($bookingsByMonth)]];
                break;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
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
