<?php

namespace App\Http\Controllers;

use App\Models\GuestReview;
use App\Models\Resort;
use Barryvdh\DomPDF\Facade\Pdf;

class GuestReviewController extends Controller
{
    public function export($resort_id)
    {
        $resort = Resort::findOrFail($resort_id);

        $reviews = GuestReview::query()
            ->with('user')
            ->where('resort_id', $resort->id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('print.guest-review', [
            'resort' => $resort,
            'reviews' => $reviews,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("GuestReviews_{$resort->name}.pdf");
    }
}
