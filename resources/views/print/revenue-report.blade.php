<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 18px;
            margin-top: 0;
            color: #555;
        }

        .header,
        .meta {
            text-align: center;
            margin-bottom: 20px;
        }

        .meta {
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px 12px;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
            font-size: 12px;
        }

        table td {
            font-size: 12px;
        }

        table td.text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Revenue Report</h1>
        <h2>{{ $title }}</h2>
    </div>

    <div class="meta">
        <div><strong>Date Generated:</strong> {{ now()->format('F j, Y H:i') }}</div>
        <div><strong>Resort Manager:</strong> {{ $managerName }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th class="text-right">Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td>{{ $row->period }}</td>
                    <td class="text-right">₱ {{ number_format($row->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align:center; color:#888; padding: 20px;">
                        No revenue data found for this period.
                    </td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td style="text-align: right;">Total:</td>
                <td class="text-right">₱ {{ number_format($overallTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
