<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmed</title>
    <style>
        body,
        p,
        h3,
        td {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #ffffff; font-family: Poppins, sans-serif; color: #333333;">

    <div
        style="max-width: 1000px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">

        <!-- Header -->
        <div
            style="background-color: #166534; color: #fff; padding: 25px; text-align: center; font-size: 24px; font-weight: bold; border-top-left-radius: 8px; border-top-right-radius: 8px;">
            Your Reservation is Confirmed!
        </div>

        {{-- Message --}}

        <div style=" padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3>Hello {{ $name }},</h3>
            <p>We’re delighted to inform you that your payment has been
                successfully verified, and your reservation at
                Canopy Farm is now officially confirmed!
            </p>
            <p>Thank you for choosing to stay with us at Canopy Farm. We’re
                excited to welcome you with warm hospitality and provide a peaceful escape where you can relax, unwind,
                and create unforgettable memories.
                Below are the details of your reservation for your review and reference.
            </p>
            <p>
                If you have any questions or concerns before your arrival, please don’t hesitate to reach out — we’re
                happy to assist you.
            </p>
            <hr style="border: none; border-top: 2px solid #cccccc; margin: 30px 0;">
            <!-- Container -->
            <table cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto; padding: 20px;">
                <tr>
                    <td>

                        <!-- Guest Details -->
                        <h3 style="color: #166534;">Guest Details</h3>
                        <p><strong>Name:</strong> {{ $name }}</p>
                        <p><strong>Email:</strong> {{ $email }}</p>
                        <p><strong>Contact Number:</strong> {{ $contact_number }}</p>

                        <!-- Accommodations -->
                        <h3 style="color: #166534; margin-top: 30px;">Accommodations</h3>
                        <table cellpadding="10" cellspacing="0"
                            style="width: 100%; border-collapse: collapse; border: 1px solid #ccc; margin: 20px auto;">
                            <tr style="background-color: #f5f5f5;">
                                <th align="left">Room</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Adults</th>
                                <th>Kids</th>
                                <th>Days</th>
                                <th>Extra</th>
                                <th>Total</th>
                            </tr>
                            @foreach ($properties as $property)
                            <tr>
                                <td>{{ $property->name_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($check_in)->format('m/d/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($check_out)->format('m/d/Y') }}</td>
                                <td>{{ $property->pivot->adults ?? '0' }}</td>
                                <td>{{ $property->pivot->kids ?? '0' }}</td>
                                <td>{{ $property->pivot->days ?? '1' }}</td>
                                <td>₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                                <td>₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}</td>
                            </tr>
                            @endforeach
                        </table>

                        <!-- Activities -->
                        @if(count($activities))
                        <h3 style="color: #166534;">Add-On Activities</h3>
                        <table cellpadding="10" cellspacing="0"
                            style="width: 100%; border-collapse: collapse; border: 1px solid #ccc; margin: 20px auto;">
                            <tr style="background-color: #f5f5f5;">
                                <th align="left">Activity</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                            @foreach ($activities as $activity)
                            <tr>
                                <td>{{ $activity->name }}</td>
                                <td>{{ $activity->pivot->quantity }}</td>
                                <td>₱{{ number_format($activity->amount, 2) }}</td>
                                <td>₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </table>
                        @endif

                        <!-- Breakdown -->
                        <h3 style="color: #166534;">Total Breakdown</h3>
                        <table width="100%" cellpadding="10" cellspacing="0"
                            style="border-collapse: collapse; border: 1px solid #ccc;">
                            <tr>
                                <td width="50%"><strong>Invoice Number:</strong></td>
                                <td>{{ $invoice_number }}</td>
                            </tr>
                            <tr style="background-color: #f5f5f5;">
                                <td><strong>Check-in Date:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($check_in)->format('F j, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Check-out Date:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($check_out)->format('F j, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Amount:</strong></td>
                                <td>₱{{ number_format($total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount Paid:</strong></td>
                                <td>₱{{ number_format($amount_paid, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Remaining Balance:</strong></td>
                                <td>₱{{ number_format($balance_due, 2) }}</td>
                            </tr>
                        </table>

                        <!-- Section Divider -->
                        <hr style="border: none; border-top: 2px solid #cccccc; margin: 30px 0;">

                        <!-- Guidelines and House Rules -->
                        <div
                            style="font-family: Poppins, sans-serif; font-size: 14px; line-height: 1.6; color: #333333;">
                            <h2 style="font-size: 18px; margin-bottom: 10px; color: #166534;">Guidelines and House Rules
                            </h2>
                            <p>This section serves as a guide which includes house rules, contacts, and key features to
                                explore.</p>

                            <p><strong>Phone:</strong> 0962 447 9893<br>
                                <strong>Full Address:</strong> 006 San Gregorio Extension, Brgy. Buna Cerca, Indang,
                                Philippines<br>
                                <strong>Waze Location:</strong> Search for <em>THE CANOPY FARM PH</em><br>
                                <strong>Gate Access:</strong> GREEN GATE for PWDs and seniors, BROWN GATE for all others
                            </p>

                            <h3 style="font-size: 16px; margin-top: 20px; color: #166534;">Check-In/Out Instructions
                            </h3>
                            <p><strong>Check-in:</strong> 3:00 PM - 6:00 PM<br>
                                <strong>Check-out:</strong> 12:00 NN
                            </p>

                            <h3 style="font-size: 16px; margin-top: 20px; color: #166534;">WiFi Access</h3>
                            <p>
                                <strong>Username:</strong> Canopyfarm<br>
                                <strong>Password:</strong> canopy_23<br><br>
                                <strong>Username:</strong> Canopyoutdoor<br>
                                <strong>Password:</strong> canopy_22<br><br>
                                <strong>Username:</strong> canopyfarmph<br>
                                <strong>Password:</strong> canopyfarmph
                            </p>

                            <h3 style="font-size: 16px; margin-top: 20px; color: #166534;">Free Activities</h3>
                            <ul style="margin: 0; padding-left: 20px;">
                                <li>Dog Exercise</li>
                                <li>Infinity Pool</li>
                                <li>Chill Out Basement</li>
                                <li>Play Area</li>
                                <li>Chill Out Space</li>
                                <li>Function Area</li>
                                <li>Hanging Bridge</li>
                            </ul>

                            <h3 style="font-size: 16px; margin-top: 20px; color: #166534;">House Rules</h3>
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

                        <!-- Final Note -->
                        <p style="font-size: 16px; margin-top: 20px;">There’s nothing more you need to do for now.
                            Just
                            get
                            ready to enjoy your stay!</p>
                        <p style="font-size: 16px;">If you have any questions or need assistance before your
                            arrival,
                            feel
                            free
                            to reach out to us.</p>
                        <p style="font-size: 16px; margin-top: 30px;"><strong>See you soon at Canopy Farm!</strong>
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            &copy; {{ date('Y') }} Canopy Farm PH. All rights reserved.
        </div>
    </div>
</body>

</html>