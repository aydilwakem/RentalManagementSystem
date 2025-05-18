<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Official Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            background-color: #166534;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .section-title {
            color: #166534;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .info-table td {
            padding: 8px;
            vertical-align: top;
        }

        .info-table td.label {
            font-weight: bold;
            color: #166534;
            width: 40%;
        }

        .amount {
            font-size: 20px;
            font-weight: bold;
            color: #166534;
        }

        .notes {
            font-style: italic;
            color: #555;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }

        .bordered {
            border: 1px solid #166534;
            padding: 10px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <div
        style="background-color: #166534; color: #fff; padding: 20px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 60px;">
        </div>
        <h1 style="font-size: 24px; font-weight: bold; text-align: center; flex-grow: 1; margin: 0;">
            Official Receipt
        </h1>
        <div></div>
    </div>

    <p class="section-title">Receipt Details</p>
    <table class="info-table bordered">
        <tr>
            <td class="label">Receipt Number:</td>
            <td>{{ $receipt->receipt_number }}</td>
        </tr>
        <tr>
            <td class="label">Receipt Date:</td>
            <td>{{ $receipt->receipt_date->format('F d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Invoice Number:</td>
            <td>{{ $invoice->invoice_number }}</td>
        </tr>
        <tr>
            <td class="label">Guest Name:</td>
            <td>
                {{ $transaction->transactionUser->first_name ?? 'N/A' }}
                {{ $transaction->transactionUser->last_name ?? '' }}
            </td>
        </tr>
        <tr>
            <td class="label">Amount Received:</td>
            <td class="amount">₱{{ number_format($receipt->amount_received, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Notes:</td>
            <td class="notes">{{ $receipt->notes ?? 'None' }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for your payment. This is your official proof of receipt.
    </div>

</body>

</html>
