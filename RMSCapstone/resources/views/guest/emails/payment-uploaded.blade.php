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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body style="margin: 0; padding: 0; background-color: #f0f0f0; font-family: Poppins, sans-serif; color: #333333;">

    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">

        {{-- Header --}}
        <div
            style="background-color: #166534; color: #fff; padding: 20px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 60px;">
            </div>
            <h1 style="font-size: 24px; font-weight: bold; text-align: center; flex-grow: 1; margin: 0;">
                Payment Received
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">Our team is now verifying it, and you'll receive a
                confirmation email shortly.</p>
        </div>

        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3 style="margin-top: 0;">Hello {{ $full_name }},</h3>

            <p>Thank you for your payment! We have successfully received your payment.
            </p>

            <p> Please wait while we confirm your reservation or update your booking. You’ll receive another email once
                everything is finalized.</p>

            {{-- Closing statement and contact --}}
            <p style="margin-top: 25px;">
                If you have any immediate questions or need to provide additional information, please don't hesitate to
                reply to this email or call us directly at <span
                    style="color:#166534; font-weight: 700;">0962-447-9893</span>.
                We're here to ensure a smooth booking experience for you.
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