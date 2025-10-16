<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Day Tour Reservation Notification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #00695c;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .email-header img {
            max-height: 70px;
            margin-bottom: 10px;
        }

        .email-body {
            padding: 30px;
            line-height: 1.6;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .details-table th,
        .details-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .details-table th {
            background-color: #f0f0f0;
        }

        .highlight {
            font-weight: bold;
            color: #00695c;
        }

        .total {
            font-weight: bold;
            font-size: 1.1em;
            text-align: right;
        }

        .footer {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #555;
        }

        .social-links a {
            margin: 0 6px;
            color: #00695c;
            text-decoration: none;
        }

        .btn {
            display: inline-block;
            background-color: #00695c;
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            @if(!empty($logo_path))
                <img src="{{ $logo_path }}" alt="Company Logo">
            @endif
            <h2>{{ $branding_company_name }}</h2>
            <p>New Day Tour Reservation Notification</p>
        </div>

        <div class="email-body">
            <p>Hello <strong>{{ $name }}</strong>,</p>
            <p>We have received a new day tour reservation under your name. Please review the reservation details below.
            </p>

            <h3>Reservation Details</h3>
            <table class="details-table">
                <tr>
                    <th>Transaction No.</th>
                    <td>{{ $transaction_number }}</td>
                </tr>
                <tr>
                    <th>Invoice No.</th>
                    <td>{{ $invoice_number }}</td>
                </tr>
                <tr>
                    <th>Tour Name</th>
                    <td>{{ $tour_name }}</td>
                </tr>
                <tr>
                    <th>Rate Type</th>
                    <td>{{ $rate_name }}</td>
                </tr>
                <tr>
                    <th>Tour Date</th>
                    <td>{{ $tour_date }}</td>
                </tr>
                <tr>
                    <th>Adult Count</th>
                    <td>{{ $adult_count }}</td>
                </tr>
                <tr>
                    <th>Kid Count</th>
                    <td>{{ $kid_count }}</td>
                </tr>
                <tr>
                    <th>Adult Rate</th>
                    <td>₱{{ number_format($adult_rate, 2) }}</td>
                </tr>
                <tr>
                    <th>Kid Rate</th>
                    <td>₱{{ number_format($kid_rate, 2) }}</td>
                </tr>
                <tr>
                    <th>Subtotal</th>
                    <td>₱{{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <th>Convenience Fee</th>
                    <td>₱{{ number_format($convenience_fee, 2) }}</td>
                </tr>
                <tr>
                    <th class="total">Total Amount</th>
                    <td class="total">₱{{ number_format($total_amount, 2) }}</td>
                </tr>
            </table>

            <p>You can proceed with your payment using the link below:</p>
            <a href="{{ $payment_link }}" class="btn">Proceed to Payment</a>

            <p>If you have any questions, feel free to reach out to us at <strong>{{ $branding_company_email }}</strong>
                or contact us at <strong>{{ $branding_company_contact }}</strong>.</p>

            <p>Address: {{ $company_address }}</p>
        </div>

        <div class="footer">
            <p>Follow us on:</p>
            <div class="social-links">
                @if(!empty($facebook_link))
                    <a href="{{ $facebook_link }}">Facebook</a>
                @endif
                @if(!empty($instagram_link))
                    <a href="{{ $instagram_link }}">Instagram</a>
                @endif
            </div>
            <p>&copy; {{ date('Y') }} {{ $branding_company_name }}. All rights reserved.</p>
        </div>
    </div>s
</body>

</html>