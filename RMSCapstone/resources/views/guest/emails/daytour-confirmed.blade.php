<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Day Tour Reservation Confirmed</title>
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

<body style="margin: 0; padding: 0; background-color: #f0f0f0; color: #333;">

    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">

        {{-- Header --}}
        <div style="background-color: #166534; color: #fff; padding: 25px 30px; text-align: center;">
            @if ($logo_path)
                <img src="{{ asset('storage/' . $logo_path) }}" alt="{{ $branding_company_name }}"
                    style="max-height: 50px; margin-bottom: 10px;">
            @endif
            <h1 style="font-size: 26px; font-weight: 700; margin: 0;">Day Tour Reservation Confirmed!</h1>
            <p style="font-size: 15px; margin: 8px 0 0;">Your booking has been successfully confirmed.</p>
        </div>

        {{-- Transaction ID --}}
        <div
            style="padding: 20px 30px; text-align: center; background-color: #f8fcf8; border-bottom: 2px solid #166534;">
            <p style="font-size: 16px; color: #555; margin: 0;">Your Transaction Number:</p>
            <h1 style="font-size: 30px; color: #166534; margin: 8px 0 0; font-weight: 700;">
                {{ $transaction_number }}
            </h1>
        </div>

        {{-- Main Body --}}
        <div style="padding: 25px 30px; line-height: 1.6; font-size: 16px;">
            <p><strong>Hi {{ $name }},</strong></p>

            <p>
                Great news! Your <strong>Day Tour Reservation</strong> with
                <strong>{{ $branding_company_name }}</strong> has been successfully confirmed.
                Please find the complete details of your booking below:
            </p>

            {{-- Reservation Details --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Reservation Details</h3>

                <table style="width:100%; border-collapse:collapse; font-size:15px;">
                    <tbody>
                        <tr>
                            <td style="padding:8px;">Tour Date:</td>
                            <td style="padding:8px; text-align:right;">
                                {{ \Carbon\Carbon::parse($tour_date)->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Adults:</td>
                            <td style="padding:8px; text-align:right;">{{ $adult_count }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Kids:</td>
                            <td style="padding:8px; text-align:right;">{{ $kid_count }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Total Guests:</td>
                            <td style="padding:8px; text-align:right;">{{ $total_guests }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Payment Summary --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Payment Summary</h3>

                <table style="width:100%; border-collapse:collapse; font-size:15px;">
                    <tbody>
                        <tr>
                            <td style="padding:8px;">Subtotal:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Convenience Fee:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($convenience_fee, 2) }}</td>
                        </tr>
                        <tr style="font-weight:bold; border-top:2px solid #166534;">
                            <td style="padding:8px;">Total Amount:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Amount Paid:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($amount_paid, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Balance Due:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($balance_due, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Invoice Details --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Invoice Details</h3>

                <table style="width:100%; border-collapse:collapse; font-size:15px;">
                    <tbody>
                        <tr>
                            <td style="padding:8px;">Invoice No.:</td>
                            <td style="padding:8px; text-align:right;">{{ $invoice_number }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Base Subtotal:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($invoice_basesubtotal, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Total Discount:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($invoice_total_discount, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Invoice Subtotal:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($invoice_subtotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Requests --}}
            @if (!empty($requests))
                <div
                    style="background-color:#fffbea; border:1px solid #f3c614; border-left:5px solid #f3c614; border-radius:5px; padding:15px; margin-bottom:20px;">
                    <h3 style="font-size:18px; color:#166534; margin-top:0;">Special Requests</h3>
                    <p><strong>Request:</strong> {{ $requests }}</p>
                    <p><strong>Reply:</strong> {{ $request_reply ?? 'No reply yet.' }}</p>
                </div>
            @endif

            {{-- Guests --}}
            @if (!empty($guest_details))
                <div
                    style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; color: #166534; margin-top: 0;">Guest Details</h3>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($guest_details as $guest)
                            <li>{{ $guest }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p>We look forward to seeing you soon for your day tour adventure!</p>

            <p style="margin-top: 20px;">
                Thank you,<br>
                <strong>{{ $branding_company_name }}</strong>
            </p>
        </div>

        {{-- Footer --}}
        <div
            style="background-color:#166534; color:#fff; text-align:center; padding:15px; font-size:14px; border-bottom-left-radius:8px; border-bottom-right-radius:8px;">
            <div style="margin-bottom:10px;">
                <p style="margin:0; font-weight:500;">Connect with us!</p>

                <a href="{{ $facebook_link }}" target="_blank" style="margin:0 8px; color:#fff; text-decoration:none;">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="{{ $instagram_link }}" target="_blank"
                    style="margin:0 8px; color:#fff; text-decoration:none;">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://canopyfarmph.com/guest/homepage" target="_blank"
                    style="margin:0 8px; color:#fff; text-decoration:none;">
                    <i class="fa-solid fa-globe"></i>
                </a>
            </div>

            <div style="font-size:0.9em; margin-bottom:10px; line-height:1.6;">
                <p style="margin:0;">Phone: <strong>{{ $branding_company_contact }}</strong></p>
                <p style="margin:0;">Address: {{ $company_address }}</p>
            </div>

            <span style="font-weight:600; display:block;">&copy; {{ date('Y') }} {{ $branding_company_name }}. All
                rights reserved.</span>
        </div>
    </div>
</body>

</html>
