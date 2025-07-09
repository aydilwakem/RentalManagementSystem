<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Canopy Farm PH - Payments Summary Report</title>
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
            margin-bottom: 15px;
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
        <h2>Payments Summary</h2>
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
            <strong>Payment Type:</strong>
            {{ $paymentTypeFilter ?: 'All' }}
        </p>
    </header>
    <p>Report generated on {{ now()->format('F d, Y h:i A') }}</p>
    <table>
        <thead>
            <tr>
                <th style="width: 6%;">#</th>
                <th style="width: 12%;">Guest Name</th>
                <th style="width: 12%;">Transaction Number</th>
                <th style="width: 10%;">Invoice Number</th>
                <th style="width: 10%;">Payment Type</th>
                <th style="width: 10%;">Payment Date</th>
                <th style="width: 10%;">Amount Paid</th>
                <th style="width: 13%;">Mode of Payment</th>
                <th style="width: 13%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td> {{ $payment->invoice->transaction->transactionUser->first_name ?? '' }}
                    {{ $payment->invoice->transaction->transactionUser->last_name ?? 'N/A' }}</td>

                <td> {{ $payment->invoice->transaction->transaction_number ?? 'N/A' }}</td>

                <td>
                    {{ $payment->invoice->invoice_number ?? 'N/A' }}
                </td>

                <td>
                    {{ $payment->payment_type ?? 'N/A' }}
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('F j, Y') }}
                </td>

                <td>{{ number_format($payment->amount_paid, 2) }}</td>

                <td>{{ ucfirst($payment->mode_of_payment) }}</td>

                <td>{{ ucfirst($payment->payment_status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px;">No payments were recorded for the
                    selected date range.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary of Key Metrics:</h2>
        <p><strong>Total Payment Records Within Date Range: </strong>{{ $totalPayments }} payment records</p>
        <p><strong>Total Amount Earned: </strong>PHP {{ number_format($totalAmount, 2) }}</p>
    </div>

    <footer>
        <div class="page-number"></div>
        &copy; {{ now()->year }} Canopy Farm PH &mdash; Report generated on {{ now()->format('F d, Y h:i A') }}
    </footer>
</body>

</html>