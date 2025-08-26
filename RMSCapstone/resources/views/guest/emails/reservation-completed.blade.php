<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation Completed</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
            <img src="{{ asset('storage/' . $logo_path) }}" alt="{{ $branding_company_name }}"
                style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px;">
                Thank you for visiting {{ $branding_company_name }}! <i class="fa-solid fa-heart"></i>
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">We hope you enjoyed your visit and look forward to
                welcoming you back soon!</p>
        </div>

        {{-- Message --}}
        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3 style="font-size: 18px; color: #333333; margin-top: 0;">Hello {{ $name }},</h3>
            <p style="margin-bottom: 15px; text-align: justify;">Thank you for choosing {{ $branding_company_name }} for
                your recent
                stay. We hope you had a relaxing and memorable time
                with us.
            </p>
            <p style="margin-bottom: 15px; text-align: justify;">It was a pleasure to have you as our guest, and we
                truly appreciate you making wonderful memories here. If there is anything we can do to make your next
                visit even better, please don’t hesitate to let us know.
            </p>
            <p style="margin-bottom: 25px; text-align: justify;">
                Safe travels, and we look forward to welcoming you back soon!
            </p>

            {{-- Guest Details --}}
            <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 10px;">Guest
                Details</h3>
            <p style="margin: 5px 0;"><strong>Name:</strong> {{ $name }}</p>
            <p style="margin: 5px 0;"><strong>Email:</strong> {{ $email }}</p>
            <p style="margin: 5px 0;"><strong>Contact Number:</strong> {{ $contact_number }}</p>

            {{-- Reservation Details --}}
            <div
                style="background-color: #E8F5E9; border-radius: 5px; padding: 20px; margin-bottom: 25px; border: 1px solid #c6f3c9; margin-top: 20px;">
                <h3 style="color: #166534; margin-top: 0; margin-bottom: 15px; font-size: 18px;">Reservation Details
                </h3>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 15px;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;"><strong>Transaction
                                Number:</strong></td>
                        <td
                            style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right; color: #166534; font-weight: bold;">
                            {{ $invoice_number }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;">Check-in Date:</td>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right;">
                            {{ \Carbon\Carbon::parse($check_in)->format('F j, Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;">Check-out Date:</td>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right;">
                            {{ \Carbon\Carbon::parse($check_out)->format('F j, Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #555;"><strong>Total Amount:</strong></td>
                        <td style="padding: 8px 0; text-align: right; color: #555;">
                            <strong>₱{{ number_format($total_amount, 2) }}</strong>
                        </td>
                    </tr>
                </table>
            </div>


            {{-- Room Details --}}
            <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">
                Accommodations</h3>
            <table cellpadding="10" cellspacing="0"
                style="width: 100%; border-collapse: collapse; margin: 15px auto 25px auto;">
                <tr style="background-color: #E8F5E9;">
                    <th align="left" style="padding: 12px; border-bottom: 1px solid #eee;">Room</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Total Pax</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Stay Duration</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Extra Person Charge</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Subtotal</th>
                </tr>
                @foreach ($properties as $property)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $property->name_number }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;  text-align: center;">
                            {{ ($property->pivot->adults ?? 0) + ($property->pivot->kids ?? 0) }}
                        </td>
                        {{-- <td style="padding: 10px; border: 1px solid #eee;">{{ $property->pivot->adults ?? '0' }}</td>
                        --}}
                        {{-- <td style="padding: 10px; border: 1px solid #eee;">{{ $property->pivot->kids ?? '0' }}</td>
                        --}}
                        <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                            {{ $property->pivot->days ?? '1' }}
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                            ₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">
                            ₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </table>

            {{-- Activities --}}
            @if (count($activities))
                <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">
                    Add-On Activities</h3>
                <table cellpadding="10" cellspacing="0"
                    style="width: 100%; border-collapse: collapse; margin: 15px auto 25px auto;">
                    <tr style="background-color: #E8F5E9;">
                        <th align="left" style="padding: 12px; border-bottom: 1px solid #eee;">Activity</th>
                        <th style="padding: 12px; border-bottom: 1px solid #eee;">Quantity</th>
                        <th style="padding: 12px; border-bottom: 1px solid #eee;">Unit Price</th>
                        <th style="padding: 12px; border-bottom: 1px solid #eee;">Subtotal</th>
                    </tr>
                    @foreach ($activities as $activity)
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $activity->name }}</td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                                {{ $activity->pivot->quantity }}
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">
                                ₱{{ number_format($activity->amount, 2) }}</td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">
                                ₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

            {{-- Services --}}
            @if (count($services))
                <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">
                    Additional Charges</h3>
                <table cellpadding="10" cellspacing="0"
                    style="width: 100%; border-collapse: collapse; margin: 15px auto 25px auto;">
                    <tr style="background-color: #E8F5E9;">
                        <th align="left" style="padding: 12px; border-bottom: 1px solid #eee;">Charge</th>
                        <th style="padding: 12px; border-bottom: 1px solid #eee;">Quantity</th>
                        <th style="padding: 12px; border-bottom: 1px solid #eee;">Unit Price</th>
                        <th style="padding: 12px; border-bottom: 1px solid #eee;">Subtotal</th>
                    </tr>
                    @foreach ($services as $service)
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $service->name }}</td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                                {{ $service->pivot->quantity ?? 1 }}
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">
                                ₱{{ number_format($service->amount, 2) }}
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">
                                ₱{{ number_format($service->amount * ($service->pivot->quantity ?? 1), 2) }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
            {{-- Price Breakdown --}}
            <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 25px;">
                <h3 style="color: #166534; margin-top: 0; margin-bottom: 10px;">Total Breakdown</h3>
                <table width="100%" cellpadding="5" cellspacing="0" style="font-size: 15px;">
                    <tr>
                        <td style="text-align: left; ">Accomodation Charges</td>
                        <td style="text-align: right;">₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding-bottom: 5px;">Activity Charges:</td>
                        <td
                            style="text-align: right; padding-bottom: 5px; {{ $balance_due > 0 ? 'color: #d9534f; font-weight: bold;' : 'color: black; font-weight: normal;' }}">
                            ₱{{ number_format($activities->sum(fn($a) => $a->amount * $a->pivot->quantity), 2) }} </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding-bottom: 5px;">Service Charges:</td>
                        <td
                            style="text-align: right; padding-bottom: 5px; {{ $balance_due > 0 ? 'color: #d9534f; font-weight: bold;' : 'color: black; font-weight: normal;' }}">
                            ₱{{ number_format($services->sum(fn($s) => $s->amount * $s->pivot->quantity), 2) }} </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="border-top: 1px solid #ddd; padding-top: 10px;"></td>
                    </tr>

                    <tr>
                        <td style="text-align: left; ">Subtotal:</td>
                        <td style="text-align: right;">₱{{ number_format($invoice_basesubtotal, 2) }}</td>
                    </tr>


                    <tr>
                        <td style="text-align: left; ">Total discount:</td>
                        <td style="text-align: right;">- ₱{{ number_format($invoice_total_discount, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; ">Convenience Fee:</td>
                        <td style="text-align: right;">₱{{ number_format($convenience_fee, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; ">Amount Paid:</td>
                        <td style="text-align: right;">₱{{ number_format($amount_paid, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; padding-bottom: 5px;">Remaining Balance:</td>
                        <td
                            style="text-align: right; padding-bottom: 5px; {{ $balance_due > 0 ? 'color: #d9534f; font-weight: bold;' : 'color: black; font-weight: normal;' }}">
                            ₱{{ number_format($balance_due, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="border-top: 1px solid #ddd; padding-top: 10px;"></td>
                    </tr>


                    <tr>
                        <td style="text-align: left; font-size: 18px; font-weight: bold;">Grand Total:</td>
                        <td style="text-align: right; font-size: 18px; font-weight: bold; color: #166534;">
                            ₱{{ number_format($total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            {{--
            <hr style="border: none; border-top: 1px solid #cccccc; margin: 30px 0;"> --}}

            <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6; text-align: center;">
                <p style="margin-bottom: 25px;">We truly value your experience with us. Help us improve by sharing your
                    thoughts:</p>

                <a href="http://127.0.0.1:8000/guest/feedback-form"
                    style="display: inline-block; background-color: #166534; color: #ffffff; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 18px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    Share Your Feedback Here!
                </a>

                <p style="font-size: 14px; color: #777; margin-top: 25px;">Your feedback helps us serve you better!</p>
            </div>

        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">

            <div style="margin-bottom: 10px;">
                <p style=" margin: 0; font-weight: 500; margin: 8px 8px;">Connect with us!</span></p>

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