<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation Successfully Submitted</title>
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
            <img src="{{ asset('storage/' . $logo_path) }}" alt=" {{ $branding_company_name }}"
                style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px; color:#fff;">
                We've Received Your Reservation!
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px; color:#fff;">Your booking is almost complete, just one more
                step to
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
                Thank you for choosing {{ $branding_company_name }}! Your reservation request has been successfully
                received. To
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

            {{-- Cart Items Table --}}
            @if(!empty($cart_items))
                <h3 style="font-size: 18px; color: #166534; margin-top: 30px; margin-bottom: 10px;">Your Selected Items</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
                    <thead>
                        <tr style="background-color: #166534; color: #fff;">
                            <th style="padding: 10px; text-align: left;">Type</th>
                            <th style="padding: 10px; text-align: left;">Name</th>
                            <th style="padding: 10px; text-align: center;">Qty / Days</th>
                            <th style="padding: 10px; text-align: right;">Total</th>
                            <th style="padding: 10px; text-align: center;">Scheduled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart_items as $item)
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 8px;">{{ $item['type'] }}</td>
                                <td style="padding: 8px;">{{ $item['name'] }}</td>
                                <td style="padding: 8px; text-align: center;">
                                    @if($item['type'] === 'Room')
                                        {{ $item['quantity'] }} days
                                    @else
                                        {{ $item['quantity'] }}
                                    @endif
                                </td>
                                <td style="padding: 8px; text-align: right;">
                                    ₱{{ number_format($item['total_amount'] ?? 0, 2) }}
                                </td>
                                <td style="padding: 8px; text-align: center;">
                                    @if(isset($item['datetime']))
                                        {{ \Carbon\Carbon::parse($item['datetime'])->format('h:i A') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif


            {{-- Financial Summary --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0; margin-bottom: 10px;">Financial Summary</h3>

                <table style="width:100%; border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td style="padding:8px;">Base Subtotal:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($base_subtotal ?? 0, 2) }}</td>
                        </tr>
                        @if(!empty($promo_code))
                            <tr>
                                <td style="padding:8px;">Promo Code ({{ $promo_code }}):</td>
                                <td style="padding:8px; text-align:right;">-₱{{ number_format($promo_amount ?? 0, 2) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding:8px;">Subtotal:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($subtotal ?? 0, 2) }}</td>
                        </tr>
                        <tr style="font-weight:bold; border-top: 2px solid #166534;">
                            <td style="padding:8px;">Total Amount:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($total_amount ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px;">Required Deposit:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($deposit ?? 0, 2) }}</td>
                        </tr>
                        {{-- <tr>
                            <td style="padding:8px;">Convenience Fee:</td>
                            <td style="padding:8px; text-align:right;">₱{{ number_format($convenience_fee ?? 0, 2) }}
                            </td>
                        </tr> --}}

                        {{-- <tr style="font-weight:bold; border-top: 2px solid #166534;">
                            <td style="padding:8px;">Total Payable Deposit Amount:</td>
                            <td style="padding:8px; text-align:right;">
                                ₱{{ number_format($total_payable_amount ?? 0, 2) }}</td>
                        </tr> --}}

                    </tbody>
                </table>
            </div>


            <p style="margin-bottom: 15px;">Please pay the required deposit within <strong style="color: #d9534f;">{{
    $expirationHours }} hours</strong> to confirm your reservation.
            </p>

            {{-- Manual Payment Options

            <a href="https://larabelles-rms.com/guest/proof-of-payment-page" target="_blank"
                style="color: #007bff; text-decoration: underline;">
                Upload Proof of Payment
            </a>:
            <br><br>
            <strong>Accepted Mode of Payments and Details:</strong><br>
            @forelse($payment_methods as $manualPaymentMethods)
            <p style="margin-bottom: 15px;">
                <strong>Bank Name:</strong>{{ $manualPaymentMethods['mode_of_payment_name'] }}<br>
                <strong>Account Name:</strong>{{ $manualPaymentMethods['account_name'] }}<br>
                <strong>Account Number:</strong>{{ $manualPaymentMethods['account_number'] }}
                <br><br>

                {{-- <strong>GCash:</strong><br>
                <strong>Account Name:</strong> Canopy Farm PH<br>
                <strong>Mobile Number:</strong> 0987654321
                <br><br>
                <strong>Maya:</strong><br>
                <strong>Account Name:</strong> Canopy Farm PH<br>
                <strong>Mobile Number:</strong> 0987654321 --}}
            </p>
            {{-- @empty
            <p>No manual payment methods are currently available.</p>
            @endforelse --}}

            {{-- Payment Link Button --}}
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ $payment_link }}" target="_blank" style="
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


            {{-- Disclaimer --}}
            <p style="text-align: center; font-size: 13px; color: #555; margin-top: -10px;">
                <em>Note: A convenience fee will be applied to your total during payment processing.</em>
            </p>

            <p style="margin-top: 25px;">
                If you need to make any changes to your reservation or have any questions, please don't hesitate to
                reach out to us.
            </p>

            <strong>Prefer to pay manually?</strong><br>
            You may use any of the following payment method options and upload your screenshot or deposit slip as proof
            of payment
            through the attached PDF File below.
            <br>

            <p style="margin-top: 20px;">
                Thank you,<br>
                {{ $branding_company_name }}
            </p>

        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">

            <div style="margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 500; margin: 8px 8px; color:#fff;">Connect with us!</span></p>

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
                <p style="margin: 0; color:#fff;">Address: {{ $company_address }}</p>
            </div>


            <span style="font-weight: 600; padding-top: 10px; display: block; color:#fff;">&copy; {{ date('Y') }} {{
    $branding_company_name }}. All rights reserved.</span>

        </div>
    </div>

</body>

</html>