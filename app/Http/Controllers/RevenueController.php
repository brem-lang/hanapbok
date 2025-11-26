<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Resort; // Assuming you have a Resort model to fetch the name
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB; // Assuming you are using dompdf

class RevenueController extends Controller
{
    public function revenueTrends(int $resort_id)
    {
        // 1. Setup and Filter Base Query
        // Fetch the resort name for the PDF title
        $resort = Resort::findOrFail($resort_id);
        $currentYear = now()->year;

        // Base query for confirmed/completed bookings at the specified resort
        $baseQuery = Booking::query()
            ->where('resort_id', $resort_id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereYear('created_at', $currentYear); // Filter for current year

        // 2. Fetch Monthly Revenue Data
        $monthlyRevenue = (clone $baseQuery)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                // Sum the total amount paid for revenue tracking
                DB::raw('SUM(amount_to_pay) as total_revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 3. Calculate New Aggregate Totals (Annual Metrics)

        // Total count of confirmed/completed bookings
        $totalBookings = (clone $baseQuery)->count();

        // Total guests who actually checked in
        $totalGuests = (clone $baseQuery)->sum('actual_check_guest');

        // 4. Prepare Monthly Data Arrays (Ensure all 12 months are present)
        $revenueData = array_fill(1, 12, 0.0);
        $overallTotalRevenue = 0;

        foreach ($monthlyRevenue as $item) {
            $revenueData[$item->month] = round($item->total_revenue, 2);
            $overallTotalRevenue += $item->total_revenue;
        }

        $chartData = array_values($revenueData); // Sequential array of monthly revenue
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // 5. Generate the PDF instance
        $pdf = Pdf::loadView('print.revenue-print', [
            'resort' => $resort,
            'currentYear' => $currentYear,
            'monthlyRevenueData' => $chartData,
            'monthLabels' => $labels,
            'overallTotalRevenue' => $overallTotalRevenue,
            'totalBookings' => $totalBookings,
            'totalGuests' => $totalGuests,
        ]);

        // Ensure necessary PDF settings for complex formatting

        // 6. Stream the PDF to the browser
        return $pdf->stream("RevenueTrendsReport-{$resort->name}-{$currentYear}.pdf");
    }
}
