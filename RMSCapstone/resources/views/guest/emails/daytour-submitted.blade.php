<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Day Tour Reservation Submitted</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Day Tour Reservation Confirmation</h2>

    <p>Hi {{ $name }},</p>
    <p>Your reservation has been successfully submitted. Here are the details:</p>

    <table cellpadding="6" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th align="left">Transaction Number</th>
            <td>{{ $transaction_number }}</td>
        </tr>
        <tr>
            <th align="left">Invoice Number</th>
            <td>{{ $invoice_number }}</td>
        </tr>
        <tr>
            <th align="left">Tour Name</th>
            <td>{{ $tour_name }}</td>
        </tr>
        <tr>
            <th align="left">Tour Date</th>
            <td>{{ $tour_date }}</td>
        </tr>
        <tr>
            <th align="left">Rate Name</th>
            <td>{{ $rate_name }}</td>
        </tr>
        <tr>
            <th align="left">Adults</th>
            <td>{{ $adult_count }} (₱{{ number_format($adult_rate, 2) }} each)</td>
        </tr>
        <tr>
            <th align="left">Kids</th>
            <td>{{ $kid_count }} (₱{{ number_format($kid_rate, 2) }} each)</td>
        </tr>
        <tr>
            <th align="left">Subtotal</th>
            <td>₱{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <th align="left">Convenience Fee</th>
            <td>₱{{ number_format($convenience_fee, 2) }}</td>
        </tr>
        <tr>
            <th align="left"><strong>Total Amount</strong></th>
            <td><strong>₱{{ number_format($total_amount, 2) }}</strong></td>
        </tr>
    </table>

    <p style="margin-top: 20px;">
        You can complete your payment using the link below:<br>
        <a href="{{ $payment_link }}" target="_blank">{{ $payment_link }}</a>
    </p>

</body>

</html>