<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Screenshot Received</title>

    <style>
        body,
        p,
        h3,
        td {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body style="background-color: #f8f8f8; font-family: Poppins, sans-serif; margin: 0; padding: 0; color: #333;">

    <div
        style="max-width: 600px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        <div
            style="background-color: #166534; color: #fff; padding: 25px; text-align: center; font-size: 24px; font-weight: bold; border-top-left-radius: 8px; border-top-right-radius: 8px;">
            Payment Confirmation
        </div>

        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3>Hello {{ $full_name }},</h3>

            <p>We have successfully received your uploaded payment screenshot. Please wait while we verify your payment.
            </p>

            <p>Here are your payment details:</p>

            <ul style="padding-left: 20px; margin-top: 10px;">
                <li style="margin-bottom: 8px;">Check-in Date: {{ $check_in }}</li>
                <li style="margin-bottom: 8px;">Check-out Date: {{ $check_out }}</li>
                <li style="margin-bottom: 8px;">Total Amount: {{ number_format($total_amount, 2) }}</li>
                <li style="margin-bottom: 8px;">Deposit: {{ number_format($deposit, 2) }}</li>
            </ul>

            <p>You will receive another email once your reservation is confirmed. If you have any questions, feel free
                to contact us.</p>

            <p>Thank you!</p>
        </div>

        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            &copy; {{ date('Y') }} Canopy Farm PH. All rights reserved.
        </div>
    </div>

</body>

</html>