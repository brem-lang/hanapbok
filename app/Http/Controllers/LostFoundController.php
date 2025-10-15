<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\Resort;
use Barryvdh\DomPDF\Facade\Pdf;

class LostFoundController extends Controller
{
    public function export($resort_id)
    {
        $resort = Resort::findOrFail($resort_id);

        $lostItems = LostItem::where('resort_id', $resort->id)
            ->latest()
            ->get();

        $title = 'Lost and Found Report';
        $date = now()->format('F d, Y');

        $pdf = Pdf::loadView('print.lost-items-report', [
            'resort' => $resort,
            'lostItems' => $lostItems,
            'title' => $title,
            'date' => $date,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("LostAndFoundReport-{$resort->name}.pdf");
    }
}
