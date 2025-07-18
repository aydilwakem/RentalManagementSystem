<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Official Receipt - Canopy Farm</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .content {
            padding: 30px
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .header,
        .footer {
            width: 100%;
            background-color: #166534;
            color: white;
            padding: 15px 0;
            margin: 0;
            text-align: center;
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

        table.no-lines,
        table.no-lines td,
        table.no-lines th,
        table.no-lines tr {
            border: none !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" />
            <h1>Official Receipt No: RCPT-00123</h1>
        </div>

        <div class="content">
            <!-- Guest Info -->
            <h3 style="color: #166534; margin-top: 0; margin-bottom: 10px; font-size: 18px;">Guest Details</h3>
            <div
                style="background-color: #E8F5E9; border-radius: 5px; padding: 15px; margin-bottom: 25px; border: 1px solid #c6f3c9;">
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 15px;" class="no-lines">
                    <tr>
                        <td style="padding: 0 0; padding-top: 0; color: #555;"><strong>Transaction Number:</strong></td>
                        <td style="padding: 8px 0; text-align: right; color: #166534; font-weight: bold;">#{{ $transaction->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #555;">Name:</td>
                        <td style="padding: 8px 0; text-align: right;">{{ $transactionUser->first_name }} {{ $transactionUser->last_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #555;">Email:</td>
                        <td style="padding: 8px 0; text-align: right;">{{ $transactionUser->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #555;">Contact Number:</td>
                        <td style="padding: 8px 0; text-align: right; color: #555;">{{ $transactionUser->contact_number }}</td>
                    </tr>
                </table>
            </div>

            <!-- Accommodations -->
            <h3>Accommodations</h3>
            <table cellpadding="10" cellspacing="0"
                style="width: 100%; border-collapse: collapse; margin: 15px auto 25px auto;">
                <tr style="background-color: #E8F5E9;">
                    <th align="left" style="padding: 12px; border-bottom: 1px solid #eee;">Room</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Check-In</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Check-Out</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Adults</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Kids</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Stay Duration</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Extra Person</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Subtotal</th>
                </tr>
                @foreach ($properties as $property)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $property->name_number }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('m/d/Y') }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ \Carbon\Carbon::parse($transaction->end_datetime)->format('m/d/Y') }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $property->pivot->adults ?? '0' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $property->pivot->kids ?? '0' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $property->pivot->days ?? '1' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </table>

            <!-- Activities -->
            @if(count($activities))
                <h3>Add-On Activities</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th style="text-align:center">Quantity</th>
                            <th style="text-align:center">Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activities as $activity)
                            <tr>
                                <td>{{ $activity->name }}</td>
                                <td style="text-align:center">{{ $activity->pivot->quantity }}</td>
                                <td style="text-align:center">₱{{ number_format($activity->amount, 2) }}</td>
                                <td>₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Receipt Details -->
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
                        <td>{{ $receipt->notes ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Feedback -->
            <div class="center">
                <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6; text-align: center;">
                    <p style="margin-bottom: 25px;">We truly value your experience with us. Help us improve by sharing
                        your
                        thoughts:</p>

                    <a href="https://canopyfarmph.com/guest/feedback-form"
                        style="display: inline-block; background-color: #166534; color: #ffffff; padding: 15px 25px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 18px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        Share Your Feedback Here!
                    </a>

                    <p style="font-size: 14px; color: #777; margin-top: 25px;">Your feedback helps us serve you better!
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Canopy Farm PH. All rights reserved.
        </div>
    </div>
</body>

</html>
