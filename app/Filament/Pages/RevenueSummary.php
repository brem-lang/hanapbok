<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class RevenueSummary extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.revenue-summary';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 9;

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
        $datasets = [];
        $labels = [];
        $user = Auth::user();

        $query = Booking::query()->whereIn('status', ['confirmed', 'completed'])->where('resort_id', $user->AdminResort?->id);

        switch ($this->filter) {
            case 'daily':
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();

                $dailyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_bookings'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'asc')
                    ->pluck('total_bookings', 'date');

                $bookingsByDay = [];
                for ($i = 0; $i < 30; $i++) {
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

    public function table(Table $table): Table
    {
        return $table
            ->query(Booking::query()->whereIn('status', ['confirmed', 'completed'])->where('resort_id', auth()->user()?->AdminResort?->id)->latest())
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('resort.name')
                    ->label('Resort')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Date From')
                    ->dateTime('F j, Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_to')
                    ->label('Date To')
                    ->dateTime('F j, Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_type')
                    ->label('Payment Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(
                        fn ($state) => match ($state) {
                            'gcash' => 'success',
                            'walk_in' => 'warning',
                            'cash' => 'danger',
                        }
                    ),
            ])
            ->filters([
                //
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
