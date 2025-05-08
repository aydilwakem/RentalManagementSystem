<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmation</title>
</head>

<body style="background-color: #f8f8f8; font-family: Arial, sans-serif; margin: 0; padding: 0; color: #333;">

    <div
        style="max-width: 600px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        <div
            style="background-color: #166534; color: #fff; padding: 25px; text-align: center; font-size: 24px; font-weight: bold; border-top-left-radius: 8px; border-top-right-radius: 8px;">
            Reservation Confirmation
        </div>

        <h1 style="text-align: center; color: #166534;">Transaction Number: {{ $transaction_number }}</h1>

        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3>Hello, {{ $name }},</h3>

            <p>Thank you for choosing Canopy Farm PH. We have successfully received your reservation and have issued you
                the following transaction number:
                <strong>{{ $transaction_number }}</strong>
            </p>

            <p>Here are your reservation details:</p>

            <ul style="padding-left: 20px; margin-top: 10px;">
                <li style="margin-bottom: 8px;">Transaction Number: {{ $transaction_number }}</li>
                <li style="margin-bottom: 8px;">Invoice Number: {{ $invoice_number }}</li>
                <li style="margin-bottom: 8px;">Check-in Date: {{ $check_in }}</li>
                <li style="margin-bottom: 8px;">Check-out Date: {{ $check_out }}</li>
                <li style="margin-bottom: 8px;">Total Amount: {{ number_format($total_amount, 2) }}</li>
                <li style="margin-bottom: 8px;">Deposit: {{ number_format($deposit, 2) }}</li>
            </ul>

            <p>Please pay the required deposit through the following link to confirm your reservation:</p>

            <p style="text-align: center; margin: 20px 0;">
                <a href="http://127.0.0.1:8000/guest/proof-of-payment-page" target="_blank"
                    style="color: #166534; text-decoration: none; font-weight: bold;">
                    http://127.0.0.1:8000/guest/proof-of-payment-page
                </a>
            </p>

            <p>If you need to make any changes to your reservation or have any questions, feel free to reach out to us!
            </p>

        </div>

        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            &copy; {{ date('Y') }} Canopy Farm PH. All rights reserved.
        </div>
    </div>

</body>

</html>