<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Day Tour Reservation Completed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
            color: #333;
        }

        .email-container {
            max-width: 650px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        h2 {
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table td {
            padding: 6px 0;
            vertical-align: top;
        }

        .footer {
            border-top: 1px solid #eee;
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 13px;
            color: #777;
        }

        .brand {
            margin-top: 10px;
            font-weight: bold;
        }

        .social a {
            text-decoration: none;
            color: #555;
            margin: 0 5px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            @if(!empty($logo_path))
                <img src="{{ $logo_path }}" alt="Company Logo">
            @endif
            <h2>Day Tour Reservation Completed</h2>
            <p>Thank you, {{ $name }}! Your day tour has been successfully completed.</p>
        </div>

        <h3>Reservation Details</h3>
        <table>
            <tr>
                <td><strong>Transaction No.:</strong></td>
                <td>{{ $transaction_number }}</td>
            </tr>
            <tr>
                <td><strong>Tour Date:</strong></td>
                <td>{{ $tour_date }}</td>
            </tr>
            <tr>
                <td><strong>Completion Date:</strong></td>
                <td>{{ $completion_date }}</td>
            </tr>
            <tr>
                <td><strong>Adults:</strong></td>
                <td>{{ $adult_count }}</td>
            </tr>
            <tr>
                <td><strong>Kids:</strong></td>
                <td>{{ $kid_count }}</td>
            </tr>
            <tr>
                <td><strong>Total Guests:</strong></td>
                <td>{{ $total_guests }}</td>
            </tr>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>₱{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Convenience Fee:</strong></td>
                <td>₱{{ number_format($convenience_fee, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Amount:</strong></td>
                <td><strong>₱{{ number_format($total_amount, 2) }}</strong></td>
            </tr>
        </table>

        <h3>Invoice Details</h3>
        <table>
            <tr>
                <td><strong>Invoice No.:</strong></td>
                <td>{{ $invoice_number }}</td>
            </tr>
            <tr>
                <td><strong>Base Subtotal:</strong></td>
                <td>₱{{ number_format($invoice_basesubtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Discount:</strong></td>
                <td>₱{{ number_format($invoice_total_discount, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Invoice Subtotal:</strong></td>
                <td>₱{{ number_format($invoice_subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Amount Paid:</strong></td>
                <td>₱{{ number_format($amount_paid, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Balance Due:</strong></td>
                <td>₱{{ number_format($balance_due, 2) }}</td>
            </tr>
        </table>

        <h3>Guest Details</h3>
        @if(!empty($guest_details) && count($guest_details) > 0)
            <ul>
                @foreach($guest_details as $guest)
                    <li>{{ $guest->name ?? 'Unnamed Guest' }}</li>
                @endforeach
            </ul>
        @else
            <p>No guest details available.</p>
        @endif

        <div class="footer">
            <p class="brand">{{ $branding_company_name }}</p>
            <p>{{ $company_address }}</p>
            <p>Email: {{ $branding_company_email }} | Contact: {{ $branding_company_contact }}</p>
            <div class="social">
                @if(!empty($facebook_link))
                    <a href="{{ $facebook_link }}">Facebook</a>
                @endif
                @if(!empty($instagram_link))
                    <a href="{{ $instagram_link }}">Instagram</a>
                @endif
            </div>
        </div>
    </div>
</body>

</html>