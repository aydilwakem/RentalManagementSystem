<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Canopy Farm PH - Reservations Summary Report</title>
    <style>
        @page {
            margin: 40px 30px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
            color: #065f46;
        }

        h2 {
            font-size: 19px;
            margin: 8px 0 4px;
            color: #065f46;
        }

        p {
            margin: 0;
            line-height: 1.5;
        }

        .date-range {
            margin-bottom: 5px;
            font-size: 13px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #c0c0c0;
            padding: 8px 10px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            background-color: #e6f7ed;
            color: #065f46;
            font-weight: bold;
            font-size: 12px;
            text-align: left;
        }

        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f0fdf4;
            border: 1px solid #a7f3d0;
            border-radius: 5px;
        }

        .summary p {
            margin: 5px 0;
            font-size: 13px;
        }

        .summary p strong {
            color: #047857;
        }

        footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #888;
        }

        .page-number {
            position: fixed;
            top: 30px;
            right: 40px;
            font-size: 11px;
            color: #666;
        }

        .page-number:after {
            content: "Page " counter(page);
        }
    </style>
</head>

<body>
    <header>
        <div class="page-number"></div>
        <img src="{{ public_path('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 50px;">
        <h1>Canopy Farm PH</h1>
        <p>006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines</p>
        <p>+63 962 447 9893</p>
        <h2>Reservations Summary</h2>
        <p class="date-range">
            <strong>Reporting Period:</strong>
            @if ($start_date && $end_date)
            {{ \Carbon\Carbon::parse($start_date)->format('F d, Y') }}
            &ndash;
            {{ \Carbon\Carbon::parse($end_date)->format('F d, Y') }}
            @else
            All Records
            @endif
        </p>
        <p class="date-range">
            <strong>Room:</strong>
            {{ $roomFilter ? $rooms->firstWhere('id', $roomFilter)?->name_number ?? 'Unknown Room' : 'All Rooms' }}

        </p>
        <p class="date-range">
            <strong>Status:</strong>
            {{ empty($reservationStatusFilter) ? 'All Statuses' : $reservationStatusFilter }}
        </p>
    </header>
    <p>Report generated on {{ now()->format('F d, Y h:i A') }}</p>
    <table>
        <thead>
            <tr>
                <th style="width: 6%;">#</th>
                <th style="width: 13%;">Transaction No.</th>
                <th style="width: 12%;">Booked By</th>
                <th style="width: 10%;">Room(s)</th>
                <th style="width: 14%;">Check-In Date</th>
                <th style="width: 14%;">Check-Out Date</th>
                <th style="width: 9%;">Total Guests</th>
                <th style="width: 11%;">Total Amount</th>
                <th style="width: 11%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transaction->transaction_number }}</td>
                <td>
                    {{ $transaction->transactionUser->first_name }}
                    {{ $transaction->transactionUser->last_name }}
                </td>
                <td>
                    @foreach ($transaction->properties as $property)
                    {{ $property->name_number ?? 'N/A' }}<br>
                    @endforeach
                </td>
                <td>{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->end_datetime)->format('M d, Y') }}</td>
                <td>{{ $transaction->pax }}</td>
                <td>{{ number_format($transaction->total_amount, 2) }}</td>
                <td>{{ $transaction->transaction_status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px;">No reservations were recorded for the
                    selected date range.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary of Key Metrics:</h2>
        <p><strong>Total Reservations Within Date Range: </strong>{{ $totalReservations }} reservations</p>
        <p><strong>Total Guests:</strong> {{ $totalGuests }} guests </p>
        <p><strong>Average Reservation Length (nights): </strong>{{ $averageLength }} nights </p>
        @if (empty($roomFilter))
        <p><strong>Most Booked Room:</strong> {{ $mostBookedRoom ?? 'No bookings found within the selected date range'
            }}</p>
        @endif
        <p><strong>Total Amount Earned: </strong>PHP {{ number_format($totalAmountEarned, 2) }}</p>
    </div>

    <footer>
        <div class="page-number"></div>
        &copy; {{ now()->year }} Canopy Farm PH &mdash; Report generated on {{ now()->format('F d, Y h:i A') }}
    </footer>
</body>

</html>