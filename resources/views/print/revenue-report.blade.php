<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-white" onload="window.print()">
    <div class="container mx-auto p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Revenue Report</h1>
            <p class="text-lg text-gray-600">{{ $title }}</p>
        </div>

        <div class="mb-6 flex justify-between text-sm text-gray-500">
            <div>
                <strong>Date Generated:</strong> {{ now()->format('F j, Y') }}
                <strong>Time Generated:</strong> {{ now()->format('F j, Y') }}
            </div>
            <div>
                <strong>Resort Manager:</strong> {{ $managerName }}
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Period
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Total Revenue
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $row)
                        <tr>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ Carbon\Carbon::parse($row->period)->format('F j, Y') }}</p>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-right">
                                <p class="text-gray-900 whitespace-no-wrap font-semibold">₱
                                    {{ number_format($row->revenue, 2) }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center py-10 text-gray-500">
                                No revenue data found for this period.
                            </td>
                        </tr>
                    @endforelse
                    <tr class="bg-gray-50 font-bold">
                        <td class="px-5 py-3 text-right text-gray-700">Total:</td>
                        <td class="px-5 py-3 text-right text-gray-800">₱ {{ number_format($data->sum('revenue'), 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
