<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt Rejected</title>

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
                Proof of Payment Rejected
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">
                Please review the reasons below and submit a new, valid proof of payment to confirm your booking.</p>
        </div>

        <div
            style="padding: 25px 30px; font-size: 16px; line-height: 1.6; color: #333; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
            <h3 style="margin-top: 0; margin-bottom: 25px; color: #2a2a2a;">Dear {{ $first_name }}
                {{ $last_name }},</h3>

            <p style="margin-bottom: 15px;">We're writing to let you know that your recently submitted payment receipt
                for your booking could not be verified.</p>

            <div
                style="background-color: #ffebeb; border: 1px solid #ef4444; border-left: 5px solid #ef4444; padding: 15px 20px; margin-bottom: 25px; border-radius: 4px;">
                <p style="margin: 0; font-size: 15px; color: #c02929;"><strong>Rejection Reason:
                        {{ $rejection_reason }}</strong></p>
            </div>

            <p style="margin-bottom: 20px;">To ensure there are no delays with your reservation, please address the
                issue mentioned above and kindly submit a new, clear, and valid proof of payment.</p>

            <p style="margin-bottom: 0;">If you have any questions, need further clarification, or require assistance,
                please don’t hesitate to contact us directly. We're here to help you finalize your booking promptly!</p>

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

                <a href="{{ $facebook_link }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    {{-- <i class="fa-brands fa-facebook" alt="Facebook"></i> --}}
                    <img src="{{ asset('images/fb-logo.png') }}" alt="Facebook" style="width: 24px; height: 24px;">
                </a>

                <a href="{{ $instagram_link }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    {{-- <i class="fa-brands fa-instagram" alt="Instagram"></i> --}}
                    <img src="{{ asset('images/ig-logo.png') }}" alt="Instagram" style="width: 24px; height: 24px;">
                </a>

                <a href="https://canopyfarmph.com/guest/homepage" target="_blank"
                    style="margin: 0 8px; text-decoration: none;">
                    {{-- <i class="fa-solid fa-globe" alt="Website"></i> --}}
                    <img src="{{ asset('images/web-logo.png') }}" alt="Website" style="width: 24px; height: 24px;">
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
