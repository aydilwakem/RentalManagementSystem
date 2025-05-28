<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmation</title>
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

        {{-- Header --}}
        <div
            style="background-color: #166534; color: #fff; padding: 25px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; text-align: center;">
            <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px;">
                We've Received Your Reservation!
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">Your booking is almost complete, just one more step to
                confirm your stay.</p>
        </div>

        {{-- Transaction Number Header --}}
        <div style="
            padding: 20px 30px;
            text-align: center;
            border-bottom: 2px solid #166534;
            background-color: #f8fcf8;">
            <p style="font-size: 18px; color: #555; margin-bottom: 5px; margin-top: 0;">Your Transaction ID:</p>
            <h1 style="font-size: 32px; color: #166534; margin: 0; font-weight: 700;">
                {{ $transaction_number }}
            </h1>
            <p style="font-size: 14px; color: #555; margin-top: 10px;">Please keep this number for all inquiries.</p>
        </div>


        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">

            <p style="margin-bottom: 15px;"><strong>Hello {{ $name }},</strong></p>

            <p style="margin-bottom: 20px;">
                Thank you for choosing Canopy Farm PH! Your reservation request has been successfully received. To
                confirm your booking and secure your unforgettable stay, please complete your payment as detailed below.
            </p>

            {{-- Disclaimer --}}
            <div style="
                background-color: #fffacd; /* Light yellow background for attention */
                border: 1px solid #e6b300; /* Yellow border */
                border-left: 5px solid #e6b300;
                border-radius: 5px;
                padding: 15px;
                margin-bottom: 25px;
                color: #333333;
                font-size: 15px;
                line-height: 1.4;">
                <strong>Note:</strong> If you have already submitted your payment or proof of payment, kindly
                disregard this email and await our confirmation. We are currently processing your submission.
            </div>


            <p style="margin-bottom: 15px;">Here are your reservation details:</p>

            {{-- Reservation Summary --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0; margin-bottom: 10px;">Reservation Details
                </h3>
                <ul style="padding-left: 0; list-style: none; margin: 0;">
                    <li style="margin-bottom: 8px;"><strong>Transaction ID: {{ $transaction_number }}</strong></li>
                    {{-- <li style="margin-bottom: 8px;">Invoice Number: {{ $invoice_number }}</li> --}}
                    <li style="margin-bottom: 8px;">Check-in Date:
                        {{ \Carbon\Carbon::parse($check_in)->format('F j, Y') }}
                    </li>
                    <li style="margin-bottom: 8px;">Check-out Date:
                        {{ \Carbon\Carbon::parse($check_out)->format('F j, Y') }}
                    </li>
                </ul>
            </div>

            {{-- Financial Summary --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0; margin-bottom: 10px;">Financial Summary</h3>
                <ul style="padding-left: 0; list-style: none; margin: 0;">
                    <li style="margin-bottom: 8px;"><strong>Total Amount:</strong>
                        ₱{{ number_format($total_amount, 2) }}</li>
                    <li style="margin-bottom: 8px;"><strong>Required Deposit:</strong> ₱{{ number_format($deposit, 2) }}
                    </li>
                </ul>
            </div>

            <p style="margin-bottom: 15px;">Please pay the required deposit within <strong
                    style="color: #d9534f;">{{ $expirationHours }} hours</strong> to confirm your reservation. You can
                upload your proof of payment through the link below:</p>

            {{-- Payment Link Button --}}
            <p style="text-align: center; margin: 20px 0;">
                <a href="http://127.0.0.1:8000/guest/proof-of-payment-page" target="_blank" style="
                    display: inline-block;
                    padding: 12px 25px;
                    background-color: #166534; /* Your brand green */
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                    font-size: 18px;
                    line-height: 1;">
                    Proceed to Payment
                </a>
            </p>


            <p style="margin-top: 25px;">
                If you need to make any changes to your reservation or have any questions, please don't hesitate to
                reach out to us.
            </p>

            <p style="margin-top: 20px;">
                Thank you,<br>
                The Canopy Farm PH
            </p>

        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">

            <div style="margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 500; margin: 8px 8px;">Connect with us!</span></p>

                <a href="https://www.facebook.com/CanopyFarmPH" target="_blank"
                    style="color: #fff; margin: 0 8px; text-decoration: none;">
                    <i class="fab fa-facebook-f fa-lg"></i>
                </a>
                <a href="https://www.instagram.com/CanopyFarmPH" target="_blank"
                    style="color: #fff; margin: 0 8px; text-decoration: none;">
                    <i class="fab fa-instagram fa-lg"></i>
                </a>
                <a href="https://twitter.com/CanopyFarmPH" target="_blank"
                    style="color: #fff; margin: 0 8px; text-decoration: none;">
                    <i class="fab fa-twitter fa-lg"></i>
                </a>
            </div>

            <div style="font-size: 0.9em; margin-bottom: 15px; line-height: 1.6;">
                <p style="margin: 0;">Phone: <span style="font-weight: 500;">0962-447-9893</span></p>
                <p style="margin: 0;">Address: 006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines</p>
            </div>


            <span style="font-weight: 600; padding-top: 10px; display: block;">&copy; {{ date('Y') }} Canopy Farm
                PH. All rights reserved.</span>

        </div>
    </div>

</body>

</html>