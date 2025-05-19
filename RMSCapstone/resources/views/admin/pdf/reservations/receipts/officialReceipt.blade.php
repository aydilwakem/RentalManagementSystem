<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Official Receipt - Canopy Farm</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            border: 1px solid #ccc;
            padding: 30px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .header,
        .footer {
            text-align: center;
            background-color: #166534;
            color: white;
            padding: 15px;
        }

        .header img {
            max-height: 60px;
        }

        h1 {
            font-size: 20px;
            margin: 10px 0;
        }

        h3 {
            color: #166534;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }

        th,
        td {
            padding: 8px 10px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f9f9f9;
            text-align: left;
        }

        td:last-child,
        th:last-child {
            text-align: right;
        }

        .info p {
            margin: 5px 0;
        }

        .center {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
        }

        a {
            color: #166534;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .note {
            font-size: 13px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" />
            <h1>Official Receipt No: {{ $receipt->receipt_number }} </h1>
        </div>

        <!-- Guest Info -->
        <h3>Guest Details</h3>
        <div class="info">
            <p><strong>Transaction No:</strong> #{{ $transaction->id }}</p>
            <p><strong>Name:</strong> {{ $transactionUser->first_name }} {{ $transactionUser->last_name }}</p>
            <p><strong>Email:</strong> {{ $transactionUser->email }}</p>
            <p><strong>Contact:</strong> {{ $transactionUser->contact_number }}</p>
        </div>

        <!-- Accommodations -->
        <h3>Accommodations</h3>
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Adults</th>
                    <th>Kids</th>
                    <th>Days</th>
                    <th>Extra</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $property)
                    <tr>
                        <td>{{ $property->name_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('m/d/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->end_datetime)->format('m/d/Y') }}</td>
                        <td>{{ $property->pivot->adults ?? '0' }}</td>
                        <td>{{ $property->pivot->kids ?? '0' }}</td>
                        <td>{{ $property->pivot->days ?? '1' }}</td>
                        <td>₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                        <td>₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Activities -->
        @if(count($activities))
            <h3>Add-On Activities</h3>
            <table>
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            <td>{{ $activity->name }}</td>
                            <td>{{ $activity->pivot->quantity }}</td>
                            <td>₱{{ number_format($activity->amount, 2) }}</td>
                            <td>₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <h3>Receipt Details</h3>
        <table>
            <tbody>
                <tr>
                    <td><strong>Receipt Number</strong></td>
                    <td>{{ $receipt->receipt_number }}</td>
                </tr>
                <tr>
                    <td><strong>Receipt Date</strong></td>
                    <td>{{ $receipt->receipt_date->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Invoice Number</strong></td>
                    <td>{{ $invoice->invoice_number }}</td>
                </tr>
                <tr style="background-color: #f5f5f5;">
                    <td><strong>Amount Received</strong></td>
                    <td><strong>₱{{ number_format($receipt->amount_received, 2) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Notes</strong></td>
                    <td>{{ $receipt->notes ?? 'None' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Feedback -->
        <div class="center">
            <p>We value your feedback!</p>
            <a href="http://127.0.0.1:8000/guest/feedback-form">Share Your Feedback Here</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Canopy Farm PH. All rights reserved.
        </div>

    </div>
</body>

</html>