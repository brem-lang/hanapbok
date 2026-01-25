<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\Resort;
use App\Models\User;
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

    public function mount()
    {
        if (auth()->user()->isGuest()) {
            abort(404);
        }
    }

    // The property to hold the current filter state.
    public string $filter = 'monthly';

    // Filter for resort admin (separate from admin filter)
    public string $resortAdminFilter = 'monthly';

    // Method to change the active filter.
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    // Method to change the resort admin filter.
    public function setResortAdminFilter(string $filter): void
    {
        $this->resortAdminFilter = $filter;
    }

    /**
     * Get base booking query with user permissions applied
     */
    protected function getBaseBookingQuery()
    {
        $user = Auth::user();
        $query = Booking::query()->whereIn('status', ['confirmed', 'completed']);

        if ($user->isResortsAdmin()) {
            $resortId = $user->AdminResort?->id;
            if ($resortId) {
                $query->where('resort_id', $resortId);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif (! $user->isAdmin()) {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    /**
     * Get base booking query for resort admin with payment validation
     */
    protected function getResortAdminBookingQuery()
    {
        $resortId = Auth::user()?->AdminResort?->id;
        
        if (!$resortId) {
            return Booking::query()->whereRaw('1 = 0');
        }

        return Booking::query()
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('resort_id', $resortId)
            ->whereNotNull('proof_of_payment');
    }

    /**
     * Get date range based on resort admin filter
     */
    protected function getResortAdminDateRange(): array
    {
        switch ($this->resortAdminFilter) {
            case 'weekly':
                return [
                    'start' => now()->subDays(6)->startOfDay(),
                    'end' => now()->endOfDay(),
                ];
            case 'annually':
                return [
                    'start' => now()->startOfYear(),
                    'end' => now()->endOfYear(),
                ];
            case 'monthly':
            default:
                return [
                    'start' => now()->startOfMonth(),
                    'end' => now()->endOfMonth(),
                ];
        }
    }

    /**
     * Get date range based on filter
     */
    protected function getDateRange(): array
    {
        switch ($this->filter) {
            case 'weekly':
                return [
                    'start' => now()->subDays(6)->startOfDay(),
                    'end' => now()->endOfDay(),
                ];
            case 'annually':
                return [
                    'start' => now()->startOfYear(),
                    'end' => now()->endOfYear(),
                ];
            case 'monthly':
            default:
                return [
                    'start' => now()->startOfMonth(),
                    'end' => now()->endOfMonth(),
                ];
        }
    }

    /**
     * Get top 10 performing resorts
     */
    #[Computed()]
    public function topResorts(): array
    {
        $dateRange = $this->getDateRange();
        $user = Auth::user();

        // Get bookings in the date range
        $bookingsQuery = Booking::query()
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);

        // Apply user permissions
        if ($user->isResortsAdmin()) {
            $resortId = $user->AdminResort?->id;
            if ($resortId) {
                $bookingsQuery->where('resort_id', $resortId);
            } else {
                return [];
            }
        } elseif (! $user->isAdmin()) {
            return [];
        }

        // Get resort statistics
        $resortStats = (clone $bookingsQuery)
            ->select(
                'resort_id',
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(amount_to_pay) as total_sales')
            )
            ->groupBy('resort_id')
            ->orderBy('total_bookings', 'desc')
            ->limit(10)
            ->get();

        // Get resort details
        $resortIds = $resortStats->pluck('resort_id')->toArray();
        $resorts = Resort::whereIn('id', $resortIds)->get()->keyBy('id');

        // Combine data
        $result = $resortStats->map(function ($stat) use ($resorts) {
            $resort = $resorts->get($stat->resort_id);
            return [
                'id' => $stat->resort_id,
                'name' => $resort->name ?? 'Unnamed Resort',
                'total_bookings' => (int) $stat->total_bookings,
                'total_sales' => (float) ($stat->total_sales ?? 0),
            ];
        })->toArray();

        return $result;
    }

    /**
     * Get bookings over time chart data
     */
    #[Computed()]
    public function bookingsOverTime(): array
    {
        $dateRange = $this->getDateRange();
        $query = $this->getBaseBookingQuery();
        $labels = [];
        $data = [];

        switch ($this->filter) {
            case 'weekly':
                $startDate = $dateRange['start'];
                $endDate = $dateRange['end'];

                $dailyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_bookings'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'asc')
                    ->pluck('total_bookings', 'date');

                for ($i = 0; $i < 7; $i++) {
                    $date = now()->subDays(6 - $i)->format('Y-m-d');
                    $labels[] = Carbon::parse($date)->format('M d');
                    $data[] = $dailyData->get($date, 0);
                }
                break;

            case 'monthly':
                $startDate = $dateRange['start'];
                $endDate = $dateRange['end'];
                $daysInMonth = $startDate->diffInDays($endDate) + 1;

                $dailyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_bookings'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'asc')
                    ->pluck('total_bookings', 'date');

                $current = $startDate->copy();
                while ($current->lte($endDate)) {
                    $dateStr = $current->format('Y-m-d');
                    $labels[] = $current->format('M d');
                    $data[] = $dailyData->get($dateStr, 0);
                    $current->addDay();
                }
                break;

            case 'annually':
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
                $data = array_values($bookingsByMonth);
                break;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    /**
     * Get sales over time chart data
     */
    #[Computed()]
    public function salesOverTime(): array
    {
        $dateRange = $this->getDateRange();
        $query = $this->getBaseBookingQuery();
        $labels = [];
        $data = [];

        switch ($this->filter) {
            case 'weekly':
                $startDate = $dateRange['start'];
                $endDate = $dateRange['end'];

                $dailyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_to_pay) as total_sales'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'asc')
                    ->pluck('total_sales', 'date');

                for ($i = 0; $i < 7; $i++) {
                    $date = now()->subDays(6 - $i)->format('Y-m-d');
                    $labels[] = Carbon::parse($date)->format('M d');
                    $data[] = (float) ($dailyData->get($date, 0));
                }
                break;

            case 'monthly':
                $startDate = $dateRange['start'];
                $endDate = $dateRange['end'];

                $dailyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_to_pay) as total_sales'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'asc')
                    ->pluck('total_sales', 'date');

                $current = $startDate->copy();
                while ($current->lte($endDate)) {
                    $dateStr = $current->format('Y-m-d');
                    $labels[] = $current->format('M d');
                    $data[] = (float) ($dailyData->get($dateStr, 0));
                    $current->addDay();
                }
                break;

            case 'annually':
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                $monthlyData = (clone $query)
                    ->whereYear('created_at', now()->year)
                    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount_to_pay) as total_sales'))
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->orderBy('month', 'asc')
                    ->pluck('total_sales', 'month');

                $salesByMonth = array_fill(1, 12, 0);
                foreach ($monthlyData as $month => $sales) {
                    $salesByMonth[$month] = (float) $sales;
                }
                $data = array_values($salesByMonth);
                break;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Sales (₱)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    /**
     * Get top resorts performance chart data
     */
    #[Computed()]
    public function topResortsChart(): array
    {
        $topResorts = $this->topResorts();

        $labels = array_column($topResorts, 'name');
        $bookingsData = array_column($topResorts, 'total_bookings');
        $salesData = array_column($topResorts, 'total_sales');

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Bookings',
                    'data' => $bookingsData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Total Sales (₱)',
                    'data' => $salesData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 1,
                    'yAxisID' => 'y1',
                ],
            ],
        ];
    }

    /**
     * Get sales over time for resort admin
     */
    #[Computed()]
    public function salesOverTimeResortAdmin(): array
    {
        $dateRange = $this->getResortAdminDateRange();
        $query = $this->getResortAdminBookingQuery();
        $labels = [];
        $data = [];

        switch ($this->resortAdminFilter) {
            case 'weekly':
                $startDate = $dateRange['start'];
                $endDate = $dateRange['end'];

                $dailyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_to_pay) as total_sales'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'asc')
                    ->pluck('total_sales', 'date');

                for ($i = 0; $i < 7; $i++) {
                    $date = now()->subDays(6 - $i)->format('Y-m-d');
                    $labels[] = Carbon::parse($date)->format('M d');
                    $data[] = (float) ($dailyData->get($date, 0));
                }
                break;

            case 'monthly':
                $startDate = $dateRange['start'];
                $endDate = $dateRange['end'];

                // Group by week for monthly view
                $weeklyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(
                        DB::raw('YEARWEEK(created_at, 1) as week'),
                        DB::raw('SUM(amount_to_pay) as total_sales')
                    )
                    ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
                    ->orderBy('week', 'asc')
                    ->get()
                    ->keyBy('week');

                $current = $startDate->copy()->startOfWeek();
                $weekCount = 0;
                while ($current->lte($endDate) && $weekCount < 6) {
                    $weekNum = (int) $current->format('oW');
                    $weekData = $weeklyData->get($weekNum);
                    $labels[] = 'Week ' . ($weekCount + 1) . ' (' . $current->format('M d') . ')';
                    $data[] = (float) ($weekData->total_sales ?? 0);
                    $current->addWeek();
                    $weekCount++;
                }
                break;

            case 'annually':
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                $monthlyData = (clone $query)
                    ->whereYear('created_at', now()->year)
                    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount_to_pay) as total_sales'))
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->orderBy('month', 'asc')
                    ->pluck('total_sales', 'month');

                $salesByMonth = array_fill(1, 12, 0);
                foreach ($monthlyData as $month => $sales) {
                    $salesByMonth[$month] = (float) $sales;
                }
                $data = array_values($salesByMonth);
                break;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Sales (₱)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    /**
     * Get booking count over time for resort admin
     */
    #[Computed()]
    public function bookingCountOverTimeResortAdmin(): array
    {
        $dateRange = $this->getResortAdminDateRange();
        $query = $this->getResortAdminBookingQuery();
        $labels = [];
        $data = [];

        switch ($this->resortAdminFilter) {
            case 'weekly':
                $startDate = $dateRange['start'];
                $endDate = $dateRange['end'];

                $dailyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_bookings'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'asc')
                    ->pluck('total_bookings', 'date');

                for ($i = 0; $i < 7; $i++) {
                    $date = now()->subDays(6 - $i)->format('Y-m-d');
                    $labels[] = Carbon::parse($date)->format('M d');
                    $data[] = (int) ($dailyData->get($date, 0));
                }
                break;

            case 'monthly':
                $startDate = $dateRange['start'];
                $endDate = $dateRange['end'];

                // Group by week for monthly view
                $weeklyData = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(
                        DB::raw('YEARWEEK(created_at, 1) as week'),
                        DB::raw('COUNT(*) as total_bookings')
                    )
                    ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
                    ->orderBy('week', 'asc')
                    ->get()
                    ->keyBy('week');

                $current = $startDate->copy()->startOfWeek();
                $weekCount = 0;
                while ($current->lte($endDate) && $weekCount < 6) {
                    $weekNum = (int) $current->format('oW');
                    $weekData = $weeklyData->get($weekNum);
                    $labels[] = 'Week ' . ($weekCount + 1) . ' (' . $current->format('M d') . ')';
                    $data[] = (int) ($weekData->total_bookings ?? 0);
                    $current->addWeek();
                    $weekCount++;
                }
                break;

            case 'annually':
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                $monthlyData = (clone $query)
                    ->whereYear('created_at', now()->year)
                    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_bookings'))
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->orderBy('month', 'asc')
                    ->pluck('total_bookings', 'month');

                $bookingsByMonth = array_fill(1, 12, 0);
                foreach ($monthlyData as $month => $bookings) {
                    $bookingsByMonth[$month] = (int) $bookings;
                }
                $data = array_values($bookingsByMonth);
                break;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    /**
     * Get user booking statistics (users with bookings vs users without bookings)
     */
    #[Computed()]
    public function userBookingStats(): array
    {
        $user = Auth::user();
        
        // Only admins can see this data
        if (!$user->isAdmin()) {
            return [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Users',
                        'data' => [],
                        'backgroundColor' => [],
                        'borderColor' => [],
                        'borderWidth' => 1,
                    ],
                ],
            ];
        }

        // Get total number of users (excluding admins and resort admins)
        $totalUsers = User::whereIn('role', ['guest'])
            ->count();

        // Get users who have at least one booking
        $usersWithBookings = User::whereIn('role', ['guest'])
            ->whereHas('bookings')
            ->count();

        // Calculate users without bookings
        $usersWithoutBookings = $totalUsers - $usersWithBookings;

        return [
            'labels' => ['Users with Bookings', 'Users without Bookings'],
            'datasets' => [
                [
                    'label' => 'Number of Users',
                    'data' => [$usersWithBookings, $usersWithoutBookings],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.7)',  // Green for users with bookings
                        'rgba(239, 68, 68, 0.7)',  // Red for users without bookings
                    ],
                    'borderColor' => [
                        'rgba(34, 197, 94, 1)',
                        'rgba(239, 68, 68, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    /**
     * Get booking status statistics
     */
    #[Computed()]
    public function bookingStatusStats(): array
    {
        $user = Auth::user();
        
        // Only admins can see this data
        if (!$user->isAdmin()) {
            return [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Bookings',
                        'data' => [],
                        'backgroundColor' => [],
                        'borderColor' => [],
                        'borderWidth' => 1,
                    ],
                ],
            ];
        }

        // Get booking counts by status
        $statusCounts = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Define all possible statuses with their display names and colors
        $statusConfig = [
            'pending' => [
                'label' => 'Pending',
                'backgroundColor' => 'rgba(251, 146, 60, 0.7)',  // Orange
                'borderColor' => 'rgba(251, 146, 60, 1)',
            ],
            'confirmed' => [
                'label' => 'Confirmed',
                'backgroundColor' => 'rgba(34, 197, 94, 0.7)',  // Green
                'borderColor' => 'rgba(34, 197, 94, 1)',
            ],
            'completed' => [
                'label' => 'Completed',
                'backgroundColor' => 'rgba(59, 130, 246, 0.7)',  // Blue
                'borderColor' => 'rgba(59, 130, 246, 1)',
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'backgroundColor' => 'rgba(239, 68, 68, 0.7)',  // Red
                'borderColor' => 'rgba(239, 68, 68, 1)',
            ],
            'moved' => [
                'label' => 'Moved',
                'backgroundColor' => 'rgba(168, 85, 247, 0.7)',  // Purple
                'borderColor' => 'rgba(168, 85, 247, 1)',
            ],
        ];

        // Build arrays for chart data
        $labels = [];
        $data = [];
        $backgroundColors = [];
        $borderColors = [];

        // Only include statuses that have bookings
        foreach ($statusConfig as $status => $config) {
            $count = $statusCounts[$status] ?? 0;
            if ($count > 0) {
                $labels[] = $config['label'];
                $data[] = $count;
                $backgroundColors[] = $config['backgroundColor'];
                $borderColors[] = $config['borderColor'];
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Number of Bookings',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    /**
     * Get walk-in vs online booking statistics for resort admin
     */
    #[Computed()]
    public function walkInVsOnlineBookingStatsResortAdmin(): array
    {
        $user = Auth::user();
        
        // Only resort admins can see this data
        if (!$user->isResortsAdmin()) {
            return [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Bookings',
                        'data' => [],
                        'backgroundColor' => [],
                        'borderColor' => [],
                        'borderWidth' => 1,
                    ],
                ],
            ];
        }

        $resortId = $user->AdminResort?->id;
        
        if (!$resortId) {
            return [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Bookings',
                        'data' => [],
                        'backgroundColor' => [],
                        'borderColor' => [],
                        'borderWidth' => 1,
                    ],
                ],
            ];
        }

        // Get booking counts by payment type for this resort
        $onlineBookings = Booking::where('resort_id', $resortId)
            ->where('payment_type', 'gcash')
            ->count();

        $walkInBookings = Booking::where('resort_id', $resortId)
            ->whereIn('payment_type', ['walk_in', 'cash'])
            ->count();

        return [
            'labels' => ['Online Bookings', 'Walk-in Guests'],
            'datasets' => [
                [
                    'label' => 'Number of Bookings',
                    'data' => [$onlineBookings, $walkInBookings],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.7)',  // Blue for online bookings
                        'rgba(251, 146, 60, 0.7)',  // Orange for walk-in guests
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(251, 146, 60, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    /**
     * Get booking status statistics for resort admin
     */
    #[Computed()]
    public function bookingStatusStatsResortAdmin(): array
    {
        $user = Auth::user();
        
        // Only resort admins can see this data
        if (!$user->isResortsAdmin()) {
            return [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Bookings',
                        'data' => [],
                        'backgroundColor' => [],
                        'borderColor' => [],
                        'borderWidth' => 1,
                    ],
                ],
            ];
        }

        $resortId = $user->AdminResort?->id;
        
        if (!$resortId) {
            return [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Bookings',
                        'data' => [],
                        'backgroundColor' => [],
                        'borderColor' => [],
                        'borderWidth' => 1,
                    ],
                ],
            ];
        }

        // Get booking counts by status for this resort
        $statusCounts = Booking::where('resort_id', $resortId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Define all possible statuses with their display names and colors
        $statusConfig = [
            'pending' => [
                'label' => 'Pending',
                'backgroundColor' => 'rgba(251, 146, 60, 0.7)',  // Orange
                'borderColor' => 'rgba(251, 146, 60, 1)',
            ],
            'confirmed' => [
                'label' => 'Confirmed',
                'backgroundColor' => 'rgba(34, 197, 94, 0.7)',  // Green
                'borderColor' => 'rgba(34, 197, 94, 1)',
            ],
            'completed' => [
                'label' => 'Completed',
                'backgroundColor' => 'rgba(59, 130, 246, 0.7)',  // Blue
                'borderColor' => 'rgba(59, 130, 246, 1)',
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'backgroundColor' => 'rgba(239, 68, 68, 0.7)',  // Red
                'borderColor' => 'rgba(239, 68, 68, 1)',
            ],
            'moved' => [
                'label' => 'Moved',
                'backgroundColor' => 'rgba(168, 85, 247, 0.7)',  // Purple
                'borderColor' => 'rgba(168, 85, 247, 1)',
            ],
        ];

        // Build arrays for chart data
        $labels = [];
        $data = [];
        $backgroundColors = [];
        $borderColors = [];

        // Only include statuses that have bookings
        foreach ($statusConfig as $status => $config) {
            $count = $statusCounts[$status] ?? 0;
            if ($count > 0) {
                $labels[] = $config['label'];
                $data[] = $count;
                $backgroundColors[] = $config['backgroundColor'];
                $borderColors[] = $config['borderColor'];
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Number of Bookings',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    /**
     * Get revenue distribution for resort admin
     */
    #[Computed()]
    public function revenueDistributionResortAdmin(): array
    {
        $dateRange = $this->getResortAdminDateRange();
        $query = $this->getResortAdminBookingQuery();

        // Get bookings in date range
        $bookings = (clone $query)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->with(['bookingItems.item', 'bookingItems.entranceFee'])
            ->get();

        $revenueByType = [];

        foreach ($bookings as $booking) {
            // Revenue from booking items (accommodation and entrance fees)
            foreach ($booking->bookingItems as $item) {
                if ($item->item_id && $item->item) {
                    $type = $item->item->type ?? 'Accommodation';
                    if (!isset($revenueByType[$type])) {
                        $revenueByType[$type] = 0;
                    }
                    $revenueByType[$type] += (float) $item->amount;
                } elseif ($item->entrance_fee_id && $item->entranceFee) {
                    $type = 'Entrance Fee';
                    if (!isset($revenueByType[$type])) {
                        $revenueByType[$type] = 0;
                    }
                    $revenueByType[$type] += (float) $item->amount;
                }
            }

            // Revenue from additional charges
            $additionalCharges = $booking->additional_charges ?? [];
            if (is_array($additionalCharges) && count($additionalCharges) > 0) {
                $totalCharges = collect($additionalCharges)->sum('total_charges');
                if ($totalCharges > 0) {
                    $type = 'Additional Services';
                    if (!isset($revenueByType[$type])) {
                        $revenueByType[$type] = 0;
                    }
                    $revenueByType[$type] += (float) $totalCharges;
                }
            }
        }

        // Sort by revenue descending
        arsort($revenueByType);

        return [
            'labels' => array_keys($revenueByType),
            'datasets' => [
                [
                    'label' => 'Revenue (₱)',
                    'data' => array_values($revenueByType),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(251, 146, 60, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(251, 146, 60, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(168, 85, 247, 1)',
                        'rgba(236, 72, 153, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
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
            'topResorts' => $this->topResorts(),
            'bookingsOverTime' => $this->bookingsOverTime(),
            'salesOverTime' => $this->salesOverTime(),
            'topResortsChart' => $this->topResortsChart(),
            'userBookingStats' => $this->userBookingStats(),
            'bookingStatusStats' => $this->bookingStatusStats(),
            'salesOverTimeResortAdmin' => $this->salesOverTimeResortAdmin(),
            'bookingCountOverTimeResortAdmin' => $this->bookingCountOverTimeResortAdmin(),
            'revenueDistributionResortAdmin' => $this->revenueDistributionResortAdmin(),
            'bookingStatusStatsResortAdmin' => $this->bookingStatusStatsResortAdmin(),
            'walkInVsOnlineBookingStatsResortAdmin' => $this->walkInVsOnlineBookingStatsResortAdmin(),
        ];
    }
}
