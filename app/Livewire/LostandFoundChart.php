<?php

namespace App\Livewire;

use App\Models\LostItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LostandFoundChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $resortId = auth()->user()?->AdminResort?->id;
        $currentYear = now()->year;

        $monthlyData = LostItem::query()
            ->where('resort_id', $resortId)
            ->whereYear('created_at', $currentYear)
            ->whereIn('type', ['lost_item', 'found_item'])
            ->select(
                'type',
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->groupBy('type', 'month')
            ->get();

        $lostData = array_fill(1, 12, 0);
        $foundData = array_fill(1, 12, 0);

        foreach ($monthlyData as $data) {
            if ($data->type === 'lost_item') {
                $lostData[$data->month] = $data->total;
            } elseif ($data->type === 'found_item') {
                $foundData[$data->month] = $data->total;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Lost Items',
                    'data' => array_values($lostData),
                    'borderColor' => '#ef4444',
                ],
                [
                    'label' => 'Found Items',
                    'data' => array_values($foundData),
                    'borderColor' => '#22c55e',
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
