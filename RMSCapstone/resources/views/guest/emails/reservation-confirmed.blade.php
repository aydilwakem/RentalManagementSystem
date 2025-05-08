<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmed</title>
</head>

<body style="margin: 0; padding: 0; background-color: #ffffff; font-family: Arial, sans-serif; color: #333333;">

    <div
        style="max-width: 900px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">

        <!-- Header -->
        <div
            style="background-color: #166534; color: #fff; padding: 25px; text-align: center; font-size: 24px; font-weight: bold; border-top-left-radius: 8px; border-top-right-radius: 8px;">
            Your Reservation is Confirmed!
        </div>

        {{-- Message --}}

        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3>Hello {{ $name }},</h3>
            <p>We’re delighted to inform you that your payment has been successfully verified, and your reservation at
                Canopy Farm is now officially confirmed!
            </p> <br>

            <p>Thank you for choosing to stay with us. We’re excited to welcome you and provide a relaxing and
                unforgettable experience.
            </p>


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

                        <!-- Final Note -->
                        <p style="font-size: 16px; margin-top: 20px;">There’s nothing more you need to do for now. Just
                            get
                            ready to enjoy your stay!</p>
                        <p style="font-size: 16px;">If you have any questions or need assistance before your arrival,
                            feel
                            free
                            to reach out to us.</p>
                        <p style="font-size: 16px; margin-top: 30px;"><strong>See you soon at Canopy Farm!</strong></p>
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