<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Day Tour Reservation</title>
    <style>
        body,
        p,
        h3,
        td {
            font-family: 'Poppins', sans-serif;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            display: block;
            -webkit-overflow-scrolling: touch;
            background-color: #fff;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            min-width: 480px;
        }

        .table-container::-webkit-scrollbar {
            height: 6px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: rgba(22, 101, 52, 0.5);
            border-radius: 10px;
        }

        td {
            white-space: nowrap;
        }

        @media only screen and (max-width: 600px) {
            .table-container {
                overflow-x: scroll !important;
                display: block !important;
                padding-bottom: 10px;
            }

            table {
                font-size: 14px !important;
                width: 100% !important;
                min-width: unset !important;
            }

            td {
                padding: 6px !important;
                white-space: normal !important;
                word-break: break-word;
            }

            h3 {
                font-size: 16px !important;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body style="margin: 0; padding: 0; background-color: #f0f0f0; color: #333;">

    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">

        {{-- Header --}}
        <div style="background-color: #166534; color: #fff; text-align: center; padding: 25px 30px;">
            @if ($logo_path)
                <img src="{{ $logo_path }}" alt="{{ $branding_company_name }}"
                    style="max-height: 50px; margin-bottom: 10px;">
            @endif
            <h1 style="font-size: 26px; font-weight: 700; margin: 0;">New Day Tour Reservation</h1>
            <p style="font-size: 15px; margin: 8px 0 0;">Thank you for choosing {{ $branding_company_name }}!</p>
        </div>

        {{-- Transaction ID --}}
        <div
            style="padding: 20px 30px; text-align: center; background-color: #f8fcf8; border-bottom: 2px solid #166534;">
            <p style="font-size: 16px; color: #555; margin: 0;">Transaction Number:</p>
            <h1 style="font-size: 30px; color: #166534; margin: 8px 0 0; font-weight: 700;">
                {{ $transaction_number }}
            </h1>
        </div>

        {{-- Main Body --}}
        <div style="padding: 25px 30px; line-height: 1.6; font-size: 16px;">
            <p><strong>Hi {{ $name }},</strong></p>
            <p>Your reservation for our <strong>Day Tour</strong> has been successfully received! Below are your
                reservation details:</p>

            {{-- Reservation Information --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Reservation Information</h3>
                <div class="table-container">
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
                                <td style="padding: 8px;">Rate Package:</td>
                                <td style="padding: 8px; text-align: right;">{{ $rate_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Guest Information --}}
            <div
                style="background-color: #f0f8f0; border: 1px solid #cde6cd; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Guest Information</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 15px;">
                    <tbody>
                        <tr>
                            <td style="padding: 8px;">Adults:</td>
                            <td style="padding: 8px; text-align: right;">{{ $adult_count }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Kids:</td>
                            <td style="padding: 8px; text-align: right;">{{ $kid_count }}</td>
                        </tr>
                        <tr style="font-weight: bold; border-top: 2px solid #166534;">
                            <td style="padding: 8px;">Total Guests:</td>
                            <td style="padding: 8px; text-align: right;">{{ $adult_count + $kid_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pricing Breakdown --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Pricing Breakdown</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 15px;">
                    <tbody>
                        <tr>
                            <td style="padding: 8px;">Adults ({{ $adult_count }} ×
                                ₱{{ number_format($adult_rate, 2) }}):</td>
                            <td style="padding: 8px; text-align: right;">
                                ₱{{ number_format($adult_count * $adult_rate, 2) }}</td>
                        </tr>
                        @if ($kid_count > 0)
                            <tr>
                                <td style="padding: 8px;">Kids ({{ $kid_count }} ×
                                    ₱{{ number_format($kid_rate, 2) }}):</td>
                                <td style="padding: 8px; text-align: right;">
                                    ₱{{ number_format($kid_count * $kid_rate, 2) }}</td>
                            </tr>
                        @endif
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

            {{-- Payment Section --}}
            @if ($payment_link)
                <div
                    style="background: #fffbea; border: 1px solid #e6b300; border-left: 5px solid #e6b300; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Complete Your Payment</h3>
                    <p>You can complete your payment using the link below:</p>
                    <a href="{{ $payment_link }}" target="_blank"
                        style="display: inline-block; padding: 12px 25px; background-color: #166534; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">Proceed
                        to Payment</a>
                </div>
            @endif

            <p>We can’t wait to welcome you to our day tour! Please keep this email for your reference.</p>

            <p style="margin-top: 20px;">
                Warm regards,<br>
                <strong>{{ $branding_company_name }}</strong>
            </p>
        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            <div style="margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 500;">Connect with us!</p>
                <a href="{{ $facebook_link }}" target="_blank"
                    style="margin: 0 8px; color: #fff; text-decoration: none;">
                    <img src="{{ asset('images/fb-logo.png') }}" alt="Facebook" style="width: 24px; height: 24px;">
                </a>
                <a href="{{ $instagram_link }}" target="_blank"
                    style="margin: 0 8px; color: #fff; text-decoration: none;">
                    <img src="{{ asset('images/ig-logo.png') }}" alt="Instagram" style="width: 24px; height: 24px;">
                </a>
                <a href="https://canopyfarmph.com/guest/homepage" target="_blank"
                    style="margin: 0 8px; color: #fff; text-decoration: none;">
                    <img src="{{ asset('images/web-logo.png') }}" alt="Website" style="width: 24px; height: 24px;">
                </a>
            </div>

            <div style="font-size: 0.9em; margin-bottom: 10px; line-height: 1.6;">
                <p style="margin: 0;">Phone: <strong>{{ $branding_company_contact }}</strong></p>
                <p style="margin: 0;">Address: {{ $company_address }}</p>
            </div>

            <span style="font-weight: 600; display: block;">&copy; {{ date('Y') }} {{ $branding_company_name }}.
                All rights reserved.</span>
        </div>
    </div>
</body>

</html>
