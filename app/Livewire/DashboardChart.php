<?php

namespace App\Livewire;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DashboardChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        // 1. Get the current year
        $currentYear = now()->year;

        // 2. Fetch booking counts per month for the current year
        $monthlyBookings = Booking::query()
            // Filter by the current year
            ->whereYear('created_at', $currentYear)
            // Select the month number (1-12) and count the bookings
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            // Group by month
            ->groupBy('month')
            // Execute the query and get the results as a collection
            ->get();

        // 3. Initialize an array for 12 months with a default count of 0
        // The keys are month numbers (1 to 12)
        $counts = array_fill(1, 12, 0);

        // 4. Populate the array with the actual booking counts
        foreach ($monthlyBookings as $booking) {
            $counts[$booking->month] = $booking->count;
        }

        // 5. Extract only the values (counts) in order (Jan to Dec)
        $data = array_values($counts);

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Bookings',
                    // Use the organized $data array
                    'data' => $data,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
