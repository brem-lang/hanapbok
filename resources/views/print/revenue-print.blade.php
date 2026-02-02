<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Revenue Trends Report - {{ $resort->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 30px;
            color: #333;
        }

        h1 {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        .rating {
            color: #f5a623;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #777;
        }

        .logo {
            text-align: center;
            margin-bottom: -80px;
            margin-top: -80px;
        }

        .logo img {
            max-height: 200px;
        }

        h3 {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .totals {
            text-align: left;
            margin-top: 0;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="logo">
        <img src="{{ public_path('img/logo1.png') }}" alt="Logo">
    </div>

    <h3>{{ $resort->name ?? 'Resort Name' }} — Revenue Trends Report ({{ $currentYear }})</h3>

    <p><strong>Date Generated:</strong> {{ now()->format('F d, Y') }}</p>

    <div class="summary">
        <p style="font-size: 14px; font-weight: bold; margin-bottom: 8px;">
            Annual Performance Summary ({{ $currentYear }})
        </p>
        <div style="display: block;">
            {{-- Total Revenue (Financial Metric) --}}
            <p class="summary-item" style="color: green; font-size: 15px;">
                Total Confirmed Revenue:
                <span style="float: right;">₱ {{ number_format($overallTotalRevenue, 2) }}</span>
            </p>

            <hr style="border-top: 1px dashed #ccc; margin: 5px 0;">

            {{-- Total Bookings (Operational Metric) --}}
            <p class="summary-item">
                Total Bookings Processed:
                <span style="float: right; font-weight: bold;">{{ number_format($totalBookings) }}</span>
            </p>

            {{-- Total Guests (Operational Metric) --}}
            <p class="summary-item">
                Total Guests Checked In:
                <span style="float: right; font-weight: bold;">{{ number_format($totalGuests) }}</span>
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 45%;">Month</th>
                <th style="width: 45%; text-align: right;">Total Revenue Earned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monthLabels as $index => $month)
                @php
                    $revenue = $monthlyRevenueData[$index] ?? 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $month }}</td>
                    <td style="text-align: right;">
                        ₱ {{ number_format($revenue, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals" style="background-color: #e0f7e0;">
                <td colspan="2" style="text-align: right; font-size: 12px; border: 2px solid #aaa;">GRAND TOTAL:</td>
                <td style="text-align: right; font-size: 12px; border: 2px solid #aaa;">₱
                    {{ number_format($overallTotalRevenue, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="totals align-left" style="margin-top:10px;">
        <span style="font-size: 14px;">Prepared By: {{ auth()->user()->name ?? 'System' }}</span><br>
        <span>Position: {{ ucfirst(auth()->user()->role) }}</span><br>
        <span>Date and Time: {{ now()->format('F d, Y h:i A') }}</span>
    </div>
</body>

</html>
