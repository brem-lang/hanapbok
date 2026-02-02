<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Guest Reviews Report - {{ $resort->name }}</title>
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

        .totals {
            text-align: left;
            margin-top: 0;
            font-size: 10px;
            font-weight: bold;
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
    </style>
</head>

<body>
    <div class="logo">
        <img src="{{ public_path('img/logo1.png') }}" alt="Logo">
    </div>

    <h3>{{ $resort->name }} — Lost and Found Report</h3>

    <p><strong>Date Generated:</strong> {{ now()->format('F d, Y') }}</p>

    <table>
        <thead style="font-size: 12px;">
            <tr>
                <th>#</th>
                <th>Reviewer</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px;">
            @forelse ($reviews as $index => $review)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $review->user?->name ?? 'Guest' }}</td>
                    <td>
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="rating">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                        @endfor
                    </td>
                    <td>{{ $review->review ?? 'No comment.' }}</td>
                    <td>{{ $review->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">No reviews found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals align-left" style="margin-top:20px;">
        <span style="font-size: 14px;">Prepared By: {{ auth()->user()->name ?? 'System' }}</span><br>
        <span>Position: {{ ucfirst(auth()->user()->role) }}</span><br>
        <span>Date and Time: {{ now()->format('F d, Y h:i A') }}</span>
    </div>
</body>

</html>
