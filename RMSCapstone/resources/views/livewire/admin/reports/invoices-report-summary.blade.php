<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Canopy Farm PH - Invoice Summary Report</title>
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
        <h2>Invoice Summary</h2>
        <p class="date-range">
            <strong>Reporting Period:</strong>
            @if ($startDate && $endDate)
            {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }}
            &ndash;
            {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}
            @else
            All Records
            @endif
        </p>

        <p class="date-range">
            <strong>Invoice Type:</strong>
            {{ $invoiceTypeFilter ?: 'All' }}
        </p>
        <p class="date-range">
            <strong>Invoice Status:</strong>
            {{ $invoiceStatusFilter ?: 'All' }}
        </p>
    </header>
    <p>Report generated on {{ now()->format('F d, Y h:i A') }}</p>
    <table>
        <thead>
            <tr>
                <th style="width: 6%;">#</th>
                <th style="width: 12%;">Invoice Number</th>
                <th style="width: 12%;">Guest Name</th>
                <th style="width: 10%;">Transaction Number</th>
                <th style="width: 10%;">Billing Date</th>
                <th style="width: 10%;">Payment Due Date</th>
                <th style="width: 10%;">Amount Paid</th>
                <th style="width: 13%;">Remaining Balance</th>
                <th style="width: 13%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $invoice->invoice_number ?? 'N/A' }}
                </td>
                <td>{{ $invoice->transaction->transactionUser->first_name ?? 'Guest Detail Has Been Deleted' }}
                    {{ $invoice->transaction->transactionUser->last_name ?? '' }}</td>

                <td>{{ $invoice->transaction->transaction_number ?? 'N/A' }}</td>

                <td>
                    {{ optional($invoice->created_at)->format('M j, Y') ?? 'N/A' }}
                </td>

                <td>
                    {{ optional($invoice->due_date)->format('M j, Y') ?? 'N/A' }}
                </td>

                <td>{{ number_format($invoice->amount_paid, 2) }}</td>

                <td>{{ number_format($invoice->balance_due, 2) }}</td>
                <td>{{ $invoice->invoice_status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px;">No invoices were recorded for the
                    selected date range.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary of Key Metrics:</h2>
        <p><strong>Total Invoice Records Within Date Range: </strong>{{ $totalInvoices }} invoice records</p>
        <p><strong>Total Amount Earned: </strong>PHP {{ number_format($totalAmountPaid, 2) }}</p>
        <p><strong>Total Remaining Balance: </strong>PHP {{ number_format($totalBalanceDue, 2) }}</p>
    </div>

    <footer>
        <div class="page-number"></div>
        &copy; {{ now()->year }} Canopy Farm PH &mdash; Report generated on {{ now()->format('F d, Y h:i A') }}
    </footer>
</body>

</html>