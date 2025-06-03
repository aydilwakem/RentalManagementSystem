<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lease Reports</title>
</head>
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
        <h1>Canopy Farm PH Property Leases</h1>
        <h2>Lease Summary</h2>
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
                <th>Tenant Representative</th>
                <th>Property Rented</th>
                <th>Total Tenats </th>
                <th>Lease Start Date</th>
                <th>Lease End Date</th>
                <th>Total Months</th>
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
                <td>{{ $transaction->pax }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}</td>
                <td>{{ $transaction->start_datetime->diffInMonths($transaction->end_datetime) + 1 }}
                    {{ Str::plural('month', $transaction->start_datetime->diffInMonths($transaction->end_datetime) + 1)
                    }}</td>
                <td>PHP{{ number_format($transaction->total_amount), 2 }}</td>
                <td>{{ ucfirst($transaction->transaction_status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No leases found in this date range.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        <h2><strong>Leases Summary: </strong></h2>
        <p><strong>Total Leases Within Date Range: </strong>{{ $totalLeases }} leases</p>
        <p><strong>Average Lease Length (months): </strong>{{ $averageLength }} months </p>
        <p><strong>Total Tenants:</strong> {{ $totalTenants }} tenants </p>
        <p><strong>Total Amount Earned: </strong>PHP {{ number_format($totalAmountEarned, 2) }}</p>

    </div>

    <footer>
        <div class="page-number"></div>
        &copy; {{ now()->year }} Canopy Farm PH &mdash; Leases Report generated on {{ now()->format('F d, Y h:i A')
        }}
    </footer>
</body>

</html>