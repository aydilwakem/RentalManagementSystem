<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Day Tour Reservation Confirmed</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            background: #fff;
            max-width: 700px;
            margin: 30px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #1e88e5;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .email-header img {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .email-body {
            padding: 25px;
            line-height: 1.6;
        }

        .section-title {
            font-weight: 600;
            margin-top: 20px;
            border-bottom: 2px solid #1e88e5;
            display: inline-block;
            padding-bottom: 4px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details-table th,
        .details-table td {
            text-align: left;
            padding: 8px 10px;
        }

        .details-table th {
            background-color: #f0f4f8;
            width: 35%;
        }

        .details-table td {
            background-color: #fafbfc;
        }

        .footer {
            background-color: #f0f4f8;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #555;
        }

        a {
            color: #1e88e5;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="email-container">
        <div class="email-header">
            @if($logo_path)
                <img src="{{ $logo_path }}" alt="{{ $branding_company_name }} Logo">
            @endif
            <h2>Day Tour Reservation Confirmed!</h2>
        </div>

        <div class="email-body">
            <p>Hi <strong>{{ $name }}</strong>,</p>
            <p>We’re happy to inform you that your <strong>Day Tour Reservation</strong> has been successfully
                confirmed.</p>

            <h3 class="section-title">Reservation Details</h3>
            <table class="details-table">
                <tr>
                    <th>Transaction No.</th>
                    <td>{{ $transaction_number }}</td>
                </tr>
                <tr>
                    <th>Tour Date</th>
                    <td>{{ $tour_date }}</td>
                </tr>
                <tr>
                    <th>Adults</th>
                    <td>{{ $adult_count }}</td>
                </tr>
                <tr>
                    <th>Kids</th>
                    <td>{{ $kid_count }}</td>
                </tr>
                <tr>
                    <th>Total Guests</th>
                    <td>{{ $total_guests }}</td>
                </tr>
            </table>

            <h3 class="section-title">Payment Summary</h3>
            <table class="details-table">
                <tr>
                    <th>Subtotal</th>
                    <td>₱{{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <th>Convenience Fee</th>
                    <td>₱{{ number_format($convenience_fee, 2) }}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td><strong>₱{{ number_format($total_amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <th>Amount Paid</th>
                    <td>₱{{ number_format($amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <th>Balance Due</th>
                    <td>₱{{ number_format($balance_due, 2) }}</td>
                </tr>
            </table>

            <h3 class="section-title">Invoice Details</h3>
            <table class="details-table">
                <tr>
                    <th>Invoice No.</th>
                    <td>{{ $invoice_number }}</td>
                </tr>
                <tr>
                    <th>Base Subtotal</th>
                    <td>₱{{ number_format($invoice_basesubtotal, 2) }}</td>
                </tr>
                <tr>
                    <th>Total Discount</th>
                    <td>₱{{ number_format($invoice_total_discount, 2) }}</td>
                </tr>
                <tr>
                    <th>Invoice Subtotal</th>
                    <td>₱{{ number_format($invoice_subtotal, 2) }}</td>
                </tr>
            </table>

            @if(!empty($requests))
                <h3 class="section-title">Special Requests</h3>
                <p><strong>Request:</strong> {{ $requests }}</p>
                <p><strong>Reply:</strong> {{ $request_reply ?? 'No reply yet.' }}</p>
            @endif

            @if(!empty($guest_details))
                <h3 class="section-title">Guest Details</h3>
                <ul>
                    @foreach($guest_details as $guest)
                        <li>{{ $guest}}</li>
                    @endforeach
                </ul>
            @endif

            <p>We look forward to having you on this tour!</p>
        </div>

        <div class="footer">
            <p><strong>{{ $branding_company_name }}</strong></p>
            <p>{{ $company_address }}<br>
                Email: <a href="mailto:{{ $branding_company_email }}">{{ $branding_company_email }}</a> |
                Contact: {{ $branding_company_contact }}</p>
            <p>
                <a href="{{ $facebook_link }}">Facebook</a> |
                <a href="{{ $instagram_link }}">Instagram</a>
            </p>
            <p>© {{ date('Y') }} {{ $branding_company_name }}. All rights reserved.</p>
        </div>
    </div>

</body>

</html>