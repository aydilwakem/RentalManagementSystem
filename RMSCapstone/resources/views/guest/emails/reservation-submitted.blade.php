<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
</head>

<body>
    <h1>Reservation Confirmation</h1>
    <p>Hello {{ $name }},</p>
    <p>Thank you for your reservation! Here are your details:</p>
    <ul>
        <li>Invoice Number: {{ $invoice_number }}</li>
        <li>Check-in Date: {{ $check_in }}</li>
        <li>Check-out Date: {{ $check_out }}</li>
        <li>Total Amount: ${{ number_format($total_amount, 2) }}</li>
        <li>Deposit: ${{ number_format($deposit, 2) }}</li>
    </ul>
    <p>If you have any questions, feel free to reach out!</p>
</body>

</html>