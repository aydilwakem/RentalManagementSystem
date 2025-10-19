<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Day Tour Reservation Submitted</title>
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

<body style="margin: 0; padding: 0; background-color: #f0f0f0; color: #333; font-family: 'Poppins', sans-serif;">

    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">

        {{-- Header --}}
        <div
            style="background-color: #166534; color: #fff; padding: 25px 30px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px;">
            <img src="{{ asset('storage/' . $logo_path) }}" alt="{{ $branding_company_name }}"
                style="max-height: 50px;">
            <h1 style="font-size: 26px; font-weight: 700; margin: 10px 0 0; color: #fff;">
                Day Tour Reservation Received!
            </h1>
            <p style="font-size: 15px; margin: 10px 0 0; color: #fff;">
                Thank you for choosing {{ $branding_company_name }}. Your booking details are below.
            </p>
        </div>

        {{-- Transaction Info --}}
        <div
            style="padding: 20px 30px; text-align: center; background-color: #f8fcf8; border-bottom: 2px solid #166534;">
            <p style="font-size: 16px; color: #555; margin: 0;">Your Transaction Number:</p>
            <h1 style="font-size: 30px; color: #166534; margin: 10px 0 0; font-weight: 700;">{{ $transaction_number }}
            </h1>
        </div>

        {{-- Body --}}
        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <p><strong>Hi {{ $name }},</strong></p>

            <p>
                We’ve successfully received your day tour reservation! Please review your booking details below and
                proceed with the payment to confirm your spot.
            </p>

            {{-- Reservation Summary --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Reservation Details</h3>

                <table style="width: 100%; border-collapse: collapse; font-size: 15px;">
                    <tbody>
                        <tr>
                            <td style="padding: 8px;">Invoice Number:</td>
                            <td style="padding: 8px; text-align: right;">{{ $invoice_number }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Tour Name:</td>
                            <td style="padding: 8px; text-align: right;">{{ $tour_name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Tour Date:</td>
                            <td style="padding: 8px; text-align: right;">
                                {{ \Carbon\Carbon::parse($tour_date)->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Rate Type:</td>
                            <td style="padding: 8px; text-align: right;">{{ $rate_name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Adults:</td>
                            <td style="padding: 8px; text-align: right;">
                                {{ $adult_count }} (₱{{ number_format($adult_rate, 2) }} each)
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Kids:</td>
                            <td style="padding: 8px; text-align: right;">
                                {{ $kid_count }} (₱{{ number_format($kid_rate, 2) }} each)
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Subtotal:</td>
                            <td style="padding: 8px; text-align: right;">₱{{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Convenience Fee:</td>
                            <td style="padding: 8px; text-align: right;">₱{{ number_format($convenience_fee, 2) }}</td>
                        </tr>
                        <tr style="font-weight: bold; border-top: 2px solid #166534;">
                            <td style="padding: 8px;">Total Amount:</td>
                            <td style="padding: 8px; text-align: right;">₱{{ number_format($total_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Payment Button --}}
            <p style="text-align: center; margin: 25px 0;">
                <a href="{{ $payment_link }}" target="_blank"
                    style="
                    display: inline-block;
                    padding: 12px 25px;
                    background-color: #166534;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                    font-size: 18px;
                    line-height: 1;">
                    Proceed to Payment
                </a>
            </p>

            <p style="text-align: center; font-size: 13px; color: #555; margin-top: -10px;">
                <em>Note: A small convenience fee will apply during checkout.</em>
            </p>

            <p>
                If you’ve already completed payment, please disregard this email. Our team will verify your transaction
                shortly.
            </p>

            <p>
                Thank you and we look forward to welcoming you soon!
            </p>

            <p style="margin-top: 20px;">
                Warm regards,<br>
                <strong>{{ $branding_company_name }}</strong>
            </p>
        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            <div style="margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 500; color: #fff;">Connect with us!</p>

                <a href="{{ $facebook_link }}" target="_blank"
                    style="margin: 0 8px; text-decoration: none; color: #fff;">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="{{ $instagram_link }}" target="_blank"
                    style="margin: 0 8px; text-decoration: none; color: #fff;">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://canopyfarmph.com/guest/homepage" target="_blank"
                    style="margin: 0 8px; text-decoration: none; color: #fff;">
                    <i class="fa-solid fa-globe"></i>
                </a>
            </div>

            <div style="font-size: 0.9em; margin-bottom: 10px; line-height: 1.6;">
                <p style="margin: 0;">Phone: <span style="font-weight: 500;">{{ $branding_company_contact }}</span></p>
                <p style="margin: 0;">Address: {{ $company_address }}</p>
            </div>

            <span style="font-weight: 600; display: block;">&copy; {{ date('Y') }}
                {{ $branding_company_name }}. All rights reserved.</span>
        </div>
    </div>
</body>

</html>
