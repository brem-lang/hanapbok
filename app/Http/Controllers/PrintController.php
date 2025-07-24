<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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
        $managerName = Auth::user()->name;

        // Start the query based on your 'bookings' table
        $query = Booking::query()
            ->select(
                DB::raw('SUM(amount_paid) as revenue')
            )
            ->where('status', 'confirmed');

        // Apply the correct grouping and date range based on the filter
        switch ($filter) {
            case 'daily':
                $title = 'Daily Revenue Report (Last 30 Days)';
                $data = $query
                    ->addSelect(DB::raw('DATE(created_at) as period'))
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('period')
                    ->orderBy('period', 'asc')
                    ->get();
                break;

            case 'yearly':
                $title = 'Yearly Revenue Report';
                $data = $query
                    ->addSelect(DB::raw('YEAR(created_at) as period'))
                    ->groupBy('period')
                    ->orderBy('period', 'asc')
                    ->get();
                break;

            case 'monthly':
            default:
                $title = 'Monthly Revenue Report for '.now()->year;
                $data = $query
                    ->addSelect(DB::raw('MONTHNAME(created_at) as period'))
                    ->whereYear('created_at', now()->year)
                    ->groupBy('period', DB::raw('MONTH(created_at)'))
                    ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
                    ->get();
                break;
        }

        // Return the printable view with all the necessary data
        return view('print.revenue-report', [
            'data' => $data,
            'title' => $title,
            'managerName' => $managerName,
        ]);
    }
}
