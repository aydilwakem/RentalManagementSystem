<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmed</title>
</head>

<body>
    <h1>Your Reservation is Confirmed!</h1>
    <p>Transaction Number: {{ $transaction_number }}</p>
    <p>Hello {{ $name }},</p>
    <p>Your reservation at Canopy Farm has been confirmed! We’re excited to welcome you. Here are your reservation
        details:</p>
    <ul>
        <li>Invoice Number: {{ $invoice_number }}</li>
        <li>Check-in Date: {{ $check_in }}</li>
        <li>Check-out Date: {{ $check_out }}</li>
        <li>Total Amount: {{ number_format($total_amount, 2) }}</li>
        <li>Deposit Paid: {{ number_format($deposit, 2) }}</li>
    </ul>

    <p>There’s nothing more you need to do for now. Just get ready to enjoy your stay!</p>
    <p>If you have any questions or need assistance before your arrival, feel free to reach out to us.</p>

    <p>See you soon at Canopy Farm!</p>
</body>

</html>