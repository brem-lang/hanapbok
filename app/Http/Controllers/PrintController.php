<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function printRevenueReport(Request $request)
    {
        // Get the filter type from the URL ('daily', 'monthly', or 'yearly')
        $filter = $request->input('filter', 'monthly');

        // Get the name of the logged-in user (assuming they are the manager)
        $user = Auth::user();

        // Start the query based on your 'bookings' table
        $query = Booking::query()
            ->where('resort_id', $user->AdminResort?->id)
            ->whereIn('status', ['confirmed', 'completed']);

        // Apply the correct grouping and date range based on the filter
        switch ($filter) {
            case 'daily':
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                $title = 'Daily Revenue Report';
                $revenueColumn = 'amount_paid';

                // The query now aliases columns to 'period' and 'total'
                $data = $query
                    ->select(
                        DB::raw("DATE_FORMAT(created_at, '%M %d, %Y') as period"),
                        DB::raw("SUM($revenueColumn) as total")
                    )
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('period')
                    ->orderByRaw('MIN(created_at) DESC')
                    ->get();
                break;

            case 'yearly':
                $startDate = now()->subYears(5)->startOfYear(); // 5 years ago
                $endDate = now()->endOfYear(); // End of this year
                $title = 'Yearly Revenue Report ('.$startDate->format('Y').' - '.$endDate->format('Y').')';
                $revenueColumn = 'amount_paid';
                // --- Part 1: Generate a list of all years in the range ---
                $allYears = [];
                for ($year = $endDate->year; $year >= $startDate->year; $year--) {
                    $allYears[$year] = 0;
                }

                // --- Part 2: Fetch the actual sales data grouped by year ---
                $salesData = $query
                    ->select(
                        DB::raw("DATE_FORMAT(created_at, '%Y') as sale_year"),
                        DB::raw("SUM($revenueColumn) as yearly_total")
                    )
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('sale_year')
                    ->get();

                // --- Part 3: Merge the sales data into the complete year list ---
                foreach ($salesData as $sale) {
                    if (isset($allYears[$sale->sale_year])) {
                        $allYears[$sale->sale_year] = $sale->yearly_total;
                    }
                }

                // --- Part 4: Standardize the final output for the view ---
                $standardizedData = [];
                foreach ($allYears as $year => $total) {
                    $standardizedData[] = (object) [
                        'period' => $year,
                        'total' => $total,
                    ];
                }
                $data = $standardizedData;
                break;

            case 'monthly':
            default:
                // 1. SETUP THE DATE RANGE
                $startDate = now()->subYear(); // 2024
                $endDate = now()->endOfMonth(); // August 2025
                $title = 'Monthly Revenue Report ('.$startDate->format('Y').' - '.$endDate->format('Y').')';
                $revenueColumn = 'amount_paid';

                // --- Part 1: Get all months (same as before) ---
                $allMonths = [];
                $period = \Carbon\CarbonPeriod::create($startDate, '1 month', $endDate);
                foreach ($period as $date) {
                    $allMonths[$date->format('F Y')] = 0;
                }

                // --- Part 2: Get sales data (same as before) ---
                $salesData = $query
                    ->select(
                        DB::raw("DATE_FORMAT(created_at, '%M %Y') as sale_month"),
                        DB::raw("SUM($revenueColumn) as monthly_total")
                    )
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('sale_month')
                    ->get();

                // --- Part 3: Merge data (same as before) ---
                foreach ($salesData as $sale) {
                    if (isset($allMonths[$sale->sale_month])) {
                        $allMonths[$sale->sale_month] = $sale->monthly_total;
                    }
                }

                // --- Part 4: Standardize the final output ---
                $standardizedData = [];
                foreach (array_reverse($allMonths, true) as $month => $total) {
                    $standardizedData[] = (object) [
                        'period' => $month,
                        'total' => $total,
                    ];
                }
                $data = $standardizedData;
                break;
        }

        $overallTotal = collect($data)->sum('total');

        // Return the printable view with all the necessary data
        // return view('print.revenue-report', [
        //     'data' => $data,
        //     'title' => $title,
        //     'managerName' => $user->name,
        //     'startDate' => $startDate,
        //     'endDate' => $endDate,
        //     'overallTotal' => $overallTotal,
        // ]);

        $pdf = Pdf::loadView('print.revenue-report', [
            'data' => $data,
            'title' => $title,
            'managerName' => $user->name,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'overallTotal' => $overallTotal,
        ]);

        return $pdf->download($filter.'-revenue-report-'.Carbon::now()->format('Y-m-d-H-i-s').'.pdf');
    }
}
