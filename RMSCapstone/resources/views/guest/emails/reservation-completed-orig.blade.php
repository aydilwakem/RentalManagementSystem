<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thank You for Visiting Canopy Farm!</title>
    <style>
        body,
        p,
        h3,
        td {
            font-family: 'Poppins', sans-serif;
            color: #333333;
        }

        a {
            color: #166534;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        th {
            background-color: #f5f5f5;
            text-align: left;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #ffffff; font-family: 'Poppins', sans-serif;">

    <div
        style="max-width: 900px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <!-- Header -->
        <div
            style="background-color: #166534; color: #fff; padding: 20px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 60px;">
            </div>
            <h1 style="font-size: 24px; font-weight: bold; text-align: center; flex-grow: 1; margin: 0;">
                Thank You for Visiting Canopy Farm!
            </h1>
            <div></div>
        </div>

        <!-- Message -->
        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6; color: #333;">
            <h3>Hello {{ $name }},</h3>
            <p>Thank you for choosing Canopy Farm for your recent stay. We hope you had a relaxing and memorable time
                with us.</p>
            <p>It was a pleasure to have you as our guest, and we truly appreciate you making wonderful memories here.
            </p>
            <p>If there is anything we can do to make your next visit even better, please don’t hesitate to let us know.
            </p>
            <p>Safe travels, and we look forward to welcoming you back soon!</p>
        </div>

        <!-- Guest Details -->
        <div style="padding: 0 30px 30px 30px;">
            <h3 style="color: #166534;">Guest Details</h3>
            <h4 style="color: #166534;">Transaction Number: {{ $transaction_number }}</h3>
                <p><strong>Name:</strong> {{ $name }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Contact Number:</strong> {{ $contact_number }}</p>

                <!-- Accommodations -->
                <h3 style="color: #166534; margin-top: 30px;">Accommodations</h3>
                <table style="width: 100%;">
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
                                <td>{{ \Carbon\Carbon::parse($check_in)->format('m/d/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($check_out)->format('m/d/Y') }}</td>
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
                    <h3 style="color: #166534; margin-top: 30px;">Add-On Activities</h3>
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Quantity</th>
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

                <!-- Breakdown -->
                <h3 style="color: #166534; margin-top: 30px;">Total Breakdown</h3>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 50%;"><strong>Invoice Number:</strong></td>
                            <td>{{ $invoice_number }}</td>
                        </tr>
                        <tr style="background-color: #f5f5f5;">
                            <td><strong>Check-in Date:</strong></td>
                            <td>{{ \Carbon\Carbon::parse($check_in)->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Check-out Date:</strong></td>
                            <td>{{ \Carbon\Carbon::parse($check_out)->format('F j, Y') }}</td>
                        </tr>
                        <tr style="background-color: #f5f5f5;">
                            <td><strong>Total Amount:</strong></td>
                            <td>₱{{ number_format($total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount Paid:</strong></td>
                            <td>₱{{ number_format($amount_paid, 2) }}</td>
                        </tr>
                        <tr style="background-color: #f5f5f5;">
                            <td><strong>Remaining Balance:</strong></td>
                            <td>₱{{ number_format($balance_due, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
        </div>

        <div class="center"
            style="text-align: center; padding: 20px; font-size: 16px; line-height: 1.6;">
            <p>We truly value your experience with us. Help us improve by sharing your thoughts through the link below:</p>
            <a href="http://127.0.0.1:8000/guest/feedback-form"
                style="color: #28a745; font-weight: bold; text-decoration: none;">
                Share Your Feedback Here!
            </a>
        </div>

        <!-- Footer -->
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            &copy; {{ date('Y') }} Canopy Farm PH. All rights reserved.
        </div>

    </div>
</body>

</html>
