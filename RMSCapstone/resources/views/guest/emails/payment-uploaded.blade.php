<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Screenshot Received</title>
</head>

<body>
    <h1>Payment Confirmation</h1>
    <p>Hello {{ $full_name }},</p>
    <p>We have successfully received your uploaded payment screenshot. Please wait while we verify your payment.</p>

    <p>Here are your payment details:</p>
    <ul>
        <li>Check-in Date: {{ $check_in }}</li>
        <li>Check-out Date: {{ $check_out }}</li>
        <li>Total Amount: {{ number_format($total_amount, 2) }}</li>
        <li>Deposit: {{ number_format($deposit, 2) }}</li>
    </ul>

    <p>You will receive another email once your reservation is confirmed. If you have any questions, feel free to
        contact
        us.</p>

    <p>Thank you!</p>
</body>

</html>