<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmed</title>
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
            <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px;">
                Your Reservation is Confirmed!
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">We're excited to welcome you!</p>
        </div>

        {{-- Message --}}
        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3 style="font-size: 18px; color: #333333; margin-top: 0;">Hello {{ $name }},</h3>
            <p style="margin-bottom: 15px; text-align: justify;">We’re delighted to inform you that your payment has
                been successfully
                verified, and your reservation at Canopy Farm is now officially confirmed!
            </p>
            <p style="margin-bottom: 15px; text-align: justify;">Thank you for choosing to stay with us at Canopy Farm.
                We’re excited to
                welcome you with warm hospitality and provide a peaceful escape where you can relax, unwind, and create
                unforgettable memories. Below are the details of your reservation for your review and reference.
            </p>
            <p style="margin-bottom: 25px; text-align: justify;">
                If you have any questions or concerns before your arrival, please don’t hesitate to reach out — we’re
                happy to assist you.
            </p>

            {{-- Reservation Details --}}
            <div
                style="background-color: #E8F5E9; border-radius: 5px; padding: 20px; margin-bottom: 25px; border: 1px solid #c6f3c9;">
                <h3 style="color: #166534; margin-top: 0; margin-bottom: 15px; font-size: 18px;">Reservation Details
                </h3>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 15px;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;"><strong>Transaction
                                Number:</strong></td>
                        <td
                            style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right; color: #166534; font-weight: bold;">
                            {{ $invoice_number }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;">Check-in Date:</td>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right;">
                            {{ \Carbon\Carbon::parse($check_in)->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;">Check-out Date:</td>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right;">
                            {{ \Carbon\Carbon::parse($check_out)->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #555;"><strong>Total Amount:</strong></td>
                        <td style="padding: 8px 0; text-align: right; color: #555;">
                            <strong>₱{{ number_format($total_amount, 2) }}</strong>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Guest Details --}}
            <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">Guest
                Details</h3>
            <p style="margin: 5px 0;"><strong>Name:</strong> {{ $name }}</p>
            <p style="margin: 5px 0;"><strong>Email:</strong> {{ $email }}</p>
            <p style="margin: 5px 0;"><strong>Contact Number:</strong> {{ $contact_number }}</p>

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
                        {{-- <td style="padding: 10px; border: 1px solid #eee;">{{ $property->pivot->adults ?? '0' }}</td> --}}
                        {{-- <td style="padding: 10px; border: 1px solid #eee;">{{ $property->pivot->kids ?? '0' }}</td> --}}
                        <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                            {{ $property->pivot->days ?? '1' }}</td>
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
                                {{ $activity->pivot->quantity }}</td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">
                                ₱{{ number_format($activity->amount, 2) }}</td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">
                                ₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

            {{-- Price Breakdown --}}
            <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 25px;">
                <h3 style="color: #166534; margin-top: 0; margin-bottom: 10px;">Total Breakdown</h3>
                <table width="100%" cellpadding="5" cellspacing="0" style="font-size: 15px;">
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

            {{-- <hr style="border: none; border-top: 1px solid #cccccc; margin: 30px 0;"> --}}

            {{-- Guidelines and House Rules --}}
            <div style="font-family: Poppins, sans-serif; font-size: 14px; line-height: 1.6; color: #333333;">
                <h2 style="font-size: 20px; font-weight: 600; margin-top: 0; margin-bottom: 15px; color: #166534;">
                    Guidelines and House Rules
                </h2>
                <p style="margin-bottom: 15px;">This section serves as a guide which includes house rules, contacts,
                    and key features to explore.</p>

                <p style="margin-bottom: 15px;"><strong>Phone:</strong> 0962 447 9893<br>
                    <strong>Full Address:</strong> 006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines<br>
                    <strong>Waze Location:</strong> Search for <em>THE CANOPY FARM PH</em><br>
                    <strong>Gate Access:</strong> GREEN GATE for PWDs and seniors, BROWN GATE for all others
                </p>

                <h3 style="font-size: 18px; font-weight: 600; margin-top: 20px; margin-bottom: 10px; color: #166534;">
                    Check-In/Out Time
                </h3>
                <p style="margin-bottom: 15px;"><strong>Check-in:</strong> 3:00 PM - 6:00 PM<br>
                    <strong>Check-out:</strong> 12:00 NN
                </p>

                <h3 style="font-size: 18px; font-weight: 600; margin-top: 20px; margin-bottom: 10px; color: #166534;">
                    WiFi
                    Access</h3>
                <p style="margin-bottom: 15px;">
                    <strong>Username:</strong> Canopyfarm<br>
                    <strong>Password:</strong> canopy_23<br><br>
                    <strong>Username:</strong> Canopyoutdoor<br>
                    <strong>Password:</strong> canopy_22<br><br>
                    <strong>Username:</strong> canopyfarmph<br>
                    <strong>Password:</strong> canopyfarmph
                </p>

                <h3 style="font-size: 18px; font-weight: 600; margin-top: 20px; margin-bottom: 10px; color: #166534;">
                    Free
                    Activities</h3>
                <ul style="margin: 0; padding-left: 20px; margin-bottom: 15px;">
                    <li>Dog Exercise</li>
                    <li>Infinity Pool</li>
                    <li>Chill Out Basement</li>
                    <li>Play Area</li>
                    <li>Chill Out Space</li>
                    <li>Function Area</li>
                    <li>Hanging Bridge</li>
                </ul>

                <h3 style="font-size: 18px; font-weight: 600; margin-top: 20px; margin-bottom: 10px; color: #166534;">
                    House
                    Rules</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Observe check-in (3:00–6:00 PM) and check-out (12:00 NN) time.</li>
                    <li>Lost keys incur a replacement fee of PHP 200.</li>
                    <li>Remove shoes before entering the house.</li>
                    <li>Wear proper swimwear. Cotton clothing is not allowed. Pool hours: 9:00 AM – 8:00 PM.
                    </li>
                    <li>No smoking on the premises.</li>
                    <li>Respect the noise curfew by 10:00 PM. No loud parties allowed.</li>
                    <li>Turn off air-conditioning and lights when leaving.</li>
                    <li>Please do not rearrange the furniture.</li>
                    <li>Only registered guests are allowed inside the premises.</li>
                </ul>
            </div>

            <p style="font-size: 16px; margin-top: 30px;">There’s nothing more you need to do for now. Just get
                ready to enjoy your stay!</p>
            <p style="font-size: 16px;">If you have any questions or need assistance before your arrival, feel
                free to reach out to us.</p>
            <p style="font-size: 18px; font-weight: 600; margin-top: 30px; text-align: center; color: #166534;">
                <strong>See
                    you soon at Canopy Farm!</strong>
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
