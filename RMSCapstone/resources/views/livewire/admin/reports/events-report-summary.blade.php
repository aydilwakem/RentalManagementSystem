<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Event Reports</title>
</head>
<style>
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

<body>
    <header>
        <h1>Canopy Farm PH</h1>
        <h2>Event Summary</h2>
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
                <th>Transaction Number</th>
                <th>Booked By</th>
                <th>Event Hall</th>
                <th>Event Type</th>
                <th>Event Start Date</th>
                <th>Event End Date</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>
                    {{ $transaction->transactionUser->first_name }}
                    {{ $transaction->transactionUser->last_name }}
                </td>
                <td>
                    @foreach ($transaction->properties as $property)
                    {{ $property->name_number ?? 'N/A' }}<br>
                    @endforeach
                </td>
                <td>{{ $transaction->event_type->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y g:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y g:i A') }}</td>
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

    <footer>
        &copy; {{ now()->year }} Canopy Farm PH &mdash; Reservation Report generated on {{ now()->format('F d, Y h:i A')
        }}
    </footer>
</body>

</html>