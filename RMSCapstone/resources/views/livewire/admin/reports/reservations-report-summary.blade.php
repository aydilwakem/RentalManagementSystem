<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reservation Summary</title>
    <style>
        @page {
            margin: 60px 40px 60px 40px;
        }

        .page-number:after {
            content: "Page " counter(page);
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            margin: 40px;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 26px;
            color: #166534;
            margin: 0;
        }

        h2 {
            font-size: 18px;
            color: #444;
            margin-bottom: 5px;
        }

        h4 {
            font-size: 12px;
            color: #444;
            margin-bottom: 3px;
        }

        .date-range {
            font-size: 13px;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background-color: #e7f2ec;
            color: #166534;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>

<body>
    <header>
        <h1>Canopy Farm PH</h1>
        <h4>006 San Gregorio Extension, Brgy. Buna Cerca , Indang, Philippines</h4>
        <h4>0962 447 9893</h4>
        <h2>Reservation Summary</h2>
        <p class="date-range">
            <strong>Date Range:</strong>
            {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('F d, Y') : 'N/A' }}
            –
            {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('F d, Y') : 'N/A' }}
        </p>
    </header>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Transaction Number</th>
                <th>Reserved By</th>
                <th>Room/s</th>
                <th>Check-In Date</th>
                <th>Check-Out Date</th>
                <th>Total Guests</th>
                <th>Total Amount</th>
                <th>Status</th>
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
                <td>PHP{{ number_format($transaction->total_amount), 2 }}</td>
                <td>{{ ucfirst($transaction->transaction_status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No reservations found in this date range.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        <h2><strong>Reservations Summary: </strong></h2>
        <p><strong>Total Reservations Within Date Range: </strong>{{ $totalReservations }} reservations</p>
        <p><strong>Total Guests:</strong> {{ $totalGuests }} guests </p>
        <p><strong>Average Reservation Length (nights): </strong>{{ $averageLength }} nights </p>
        <p><strong>Total Amount Earned: </strong>PHP {{ number_format($totalAmountEarned, 2) }}</p>

    </div>

    <footer>
        <div class="page-number"></div>
        &copy; {{ now()->year }} Canopy Farm PH &mdash; Reservation Report generated on {{ now()->format('F d, Y h:i A')
        }}
    </footer>
</body>

</html>