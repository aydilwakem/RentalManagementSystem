<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Settle Your Balance Before Checkout</title>
    <style>
        body,
        p,
        h3,
        td {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body style="margin: 0; padding: 0; background-color: #f0f0f0; font-family: Poppins, sans-serif; color: #333333;">

    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">

        <!-- Header -->
        <div
            style="background-color: #166534; color: #fff; padding: 25px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; text-align: center;">
            <img src="{{ asset('storage/' . $logo_path) }}" alt=" {{ $branding_company_name }}"
                style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px;">
                Final Payment Reminder
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">Please complete your payment before checkout</p>
        </div>

        <!-- Transaction Number -->
        <div
            style="padding: 20px 30px; text-align: center; border-bottom: 2px solid #166534; background-color: #f8fcf8;">
            <p style="font-size: 18px; color: #555; margin-bottom: 5px; margin-top: 0;">Your Transaction ID:</p>
            <h1 style="font-size: 32px; color: #166534; margin: 0; font-weight: 700;">
                {{ $transaction_number }}
            </h1>
        </div>

        <!-- Body -->
        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <p><strong>Hello {{ $name }},</strong></p>

            <p>
                We hope you’ve had a wonderful time at {{ $branding_company_name }}! Before you leave the resort, we
                kindly remind you
                to complete the final payment for your stay.
            </p>

            <!-- Reservation Summary -->
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin: 20px 0;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Reservation Summary</h3>
                <ul style="padding-left: 0; list-style: none; margin: 0;">
                    <li><strong>Transaction ID:</strong> {{ $transaction_number }}</li>
                    <li><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($check_in)->format('F j, Y') }}</li>
                    <li><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($check_out)->format('F j, Y') }}</li>
                    <li><strong>Total Amount:</strong> ₱{{ number_format($sub_total, 2) }}</li>
                    <li><strong>Amount Paid:</strong> ₱{{ number_format($amount_paid, 2) }}</li>
                    <li><strong>Remaining Balance:</strong> ₱{{ number_format($remaining_balance, 2) }}</li>
                </ul>
            </div>

            <p>To avoid any delays during checkout, please settle your remaining balance by clicking the button below:
            </p>

            <!-- Payment Button -->
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $payment_link }}" target="_blank" style="
                    display: inline-block;
                    padding: 14px 30px;
                    background-color: #166534;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: bold;
                    font-size: 18px;">
                    Pay Remaining Balance
                </a>
            </p>


            {{-- Disclaimer --}}
            <p style="text-align: center; font-size: 13px; color: #555; margin-top: -10px;">
                <em>Note: A convenience fee will be applied to your total during payment processing.</em>
            </p>

            {{-- Manual Payment Options --}}
            {{-- <p style="margin-bottom: 15px;">
                <strong>Prefer to pay manually?</strong><br>
                You may use any of the following options and upload your screenshot or deposit slip as proof of payment:
                <br><br>
                <strong>Bank Transfer:</strong><br>
                <strong>Bank Name:</strong> Landbank<br>
                <strong>Account Name:</strong> Canopy Farm PH<br>
                <strong>Account Number:</strong> 00-2340-179315
                <br><br>
                <strong>GCash:</strong><br>
                <strong>Account Name:</strong> Canopy Farm PH<br>
                <strong>Mobile Number:</strong> 0987654321
                <br><br>
                <strong>Maya:</strong><br>
                <strong>Account Name:</strong> Canopy Farm PH<br>
                <strong>Mobile Number:</strong> 0987654321
            </p>

            <a href="https://larabelles-rms.com/guest/proof-of-payment-page" target="_blank"
                style="color: #337ab7; text-decoration: underline;">
                Click here to upload your proof of payment
            </a> --}}

            <strong>Prefer to pay manually?</strong><br>
            You may use any of the following payment method options and upload your screenshot or deposit slip as proof
            of payment
            through the attached PDF File below.
            <br>

            <p>If you’ve already completed the payment, please ignore this message. Otherwise, we appreciate your prompt
                attention.</p>

            <p>Thank you for choosing {{ $branding_company_name }}. We hope to see you again soon!</p>

            <p>Best regards,<br>The {{ $branding_company_name }}</p>
        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">

            <div style="margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 500; margin: 8px 8px;">Connect with us!</span></p>

                <a href="{{ $facebook_link }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    <img src="{{ asset('images/fb-logo.png') }}" alt="Facebook" style="width: 24px; height: 24px;">
                </a>

                <a href="{{ $instagram_link }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    <img src="{{ asset('images/ig-logo.png') }}" alt="Instagram" style="width: 24px; height: 24px;">
                </a>

                <a href="https://larabelles-rms.com/guest/homepage" target="_blank"
                    style="margin: 0 8px; text-decoration: none;">
                    <img src="{{ asset('images/web-logo.png') }}" alt="Website" style="width: 24px; height: 24px;">
                </a>
            </div>

            <div style="font-size: 0.9em; margin-bottom: 15px; line-height: 1.6; color:#fff;">
                <p style="margin: 0;">Phone: <span style="font-weight: 500;">{{ $branding_company_contact }}</span></p>
                <p style="margin: 0;">Address: {{ $company_address }}</p>
            </div>


            <span style="font-weight: 600; padding-top: 10px; display: block; color:#fff;">&copy; {{ date('Y') }} {{
    $branding_company_name }}. All rights reserved.</span>
        </div>
    </div>

</body>

</html>