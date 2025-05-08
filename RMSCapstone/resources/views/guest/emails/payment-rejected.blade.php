<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt Rejected</title>
</head>

<body>
    <h1>Dear {{ $user_email }},</h1>

    <p>Your payment receipt has been rejected due to the following reason:</p>

    <p><strong>Rejection Reason:</strong> {{ $rejection_reason }}</p>

    <p>If you have any questions or need further assistance, feel free to contact us.</p>

    <p>Best regards,<br>Support Team</p>
</body>

</html>