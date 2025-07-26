<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->where('status', 'confirmed');

        // Apply the correct grouping and date range based on the filter
        switch ($filter) {
            case 'daily':
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                $title = 'Daily Revenue Report (Last 30 Days) '.$startDate->format('F j,').' to '.$endDate->format('F j, Y');

                $data = $query
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'yearly':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                $title = 'Yearly Revenue Report for '.now()->year;

                $data = $query
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->get();

                break;

            case 'monthly':
            default:
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                $title = 'Monthly Revenue Report for '.$startDate->format('F Y').' to '.$endDate->format('F Y');

                $data = $query
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
        }

        // Return the printable view with all the necessary data
        return view('print.revenue-report', [
            'data' => $data,
            'title' => $title,
            'managerName' => $user->name,
        ]);
    }
}
