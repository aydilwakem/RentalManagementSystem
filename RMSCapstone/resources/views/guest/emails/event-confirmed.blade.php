<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Event Confirmed!</title>
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
            <img src="{{ asset('storage/' . $branding->logo) }}" alt=" {{ $branding->company_name }}"
                style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px;">
                Your Event is Confirmed!
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">We're excited to welcome you!</p>
        </div>

        {{-- Message --}}
        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3 style="font-size: 18px; color: #333333; margin-top: 0;">Hello {{ $event->transactionUser->first_name }}
                {{$event->transactionUser->last_name }},</h3>
            <p style="margin-bottom: 15px; text-align: justify;">We’re delighted to inform you that your down payment
                has
                been successfully
                verified, and your event at {{ $branding->company_name}} is now officially confirmed!
            </p>
            <p style="margin-bottom: 15px; text-align: justify;">Thank you for choosing to stay with us at {{
                $branding->company_name }}.
                We’re excited to
                welcome you with warm hospitality and provide an unforgettable event for you! Below are the details of
                your event booking for your review and reference.
            </p>
            <p style="margin-bottom: 25px; text-align: justify;">
                If you have any questions or concerns before your event, please don’t hesitate to reach out — we’re
                happy to assist you.
            </p>

            {{-- Event Details --}}
            <div
                style="background-color: #E8F5E9; border-radius: 5px; padding: 20px; margin-bottom: 25px; border: 1px solid #c6f3c9;">
                <h3 style="color: #166534; margin-top: 0; margin-bottom: 15px; font-size: 18px;">Event Details
                </h3>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 15px;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;"><strong>Transaction
                                Number:</strong></td>
                        <td
                            style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right; color: #166534; font-weight: bold;">
                            {{ $event->transaction_number }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;">Event Start Date and
                            Time:
                        </td>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right;">
                            {{ ($event->start_datetime)->format('F j, Y, g:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; color: #555;">Event End Date and
                            Time:</td>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #e0e0e0; text-align: right;">
                            {{ ($event->end_datetime)->format('F j, Y, g:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #555;"><strong>Total Amount:</strong></td>
                        <td style="padding: 8px 0; text-align: right; color: #555;">
                            <strong>₱{{ number_format($event->total_amount, 2) }}</strong>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Guest Details --}}
            <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">Guest
                Details</h3>
            <p style="margin: 5px 0;"><strong>Name:</strong> {{ $event->transactionUser->first_name }} {{
                $event->transactionUser->last_name }}</p>
            <p style="margin: 5px 0;"><strong>Email:</strong> {{ $event->transactionUser->email }}</p>
            <p style="margin: 5px 0;"><strong>Contact Number:</strong> {{ $event->transactionUser->contact_number }}</p>


            {{-- Event Hall Details --}}
            <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">
                Event Hall</h3>
            <table cellpadding="10" cellspacing="0"
                style="width: 100%; border-collapse: collapse; margin: 15px auto 25px auto;">
                <tr style="background-color: #E8F5E9;">
                    <th align="left" style="padding: 12px; border-bottom: 1px solid #eee;">Event Hall</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Total Pax</th>
                </tr>
                @foreach ($properties as $property)
                @if ($property->property_type_id == 3)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $property->name_number }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;  text-align: center;">
                        {{ ($property->pivot->adults ?? 0) + ($property->pivot->kids ?? 0) }}
                    </td>
                    {{-- <td style="padding: 10px; border: 1px solid #eee;">{{ $property->pivot->adults ?? '0' }}</td>
                    --}}
                    {{-- <td style="padding: 10px; border: 1px solid #eee;">{{ $property->pivot->kids ?? '0' }}</td>
                    --}}
                </tr>
                @endif
                @endforeach
            </table>

            {{-- Room Details --}}
            <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">
                Rooms</h3>
            <table cellpadding="10" cellspacing="0"
                style="width: 100%; border-collapse: collapse; margin: 15px auto 25px auto;">
                <tr style="background-color: #E8F5E9;">
                    <th align="left" style="padding: 12px; border-bottom: 1px solid #eee;">Room</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Room Category</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Ideal Guests</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Maximum Occupancy</th>
                </tr>
                @foreach ($properties as $property)
                @if ($property->property_type_id == 1)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $property->name_number }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;  text-align: center;">
                        {{ $property->category->name ?? 'N/A' }}
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;  text-align: center;">
                        {{ $property->ideal_guest ?? 'N/A' }}
                    </td>
                    @if ($property->occupancy_type === 'whole_number')
                    <td style="padding: 12px; border-bottom: 1px solid #eee;  text-align: center;">
                        {{ $property->max_guests ?? 'N/A' }}
                    </td>
                    @elseif ($property->occupancy_type === 'combinations')
                    @php
                    $originalCombinations = collect($property->occupancy_rules)
                    ->where('type', 'original');

                    $formatted = $originalCombinations->map(function ($combo) {
                    $parts = [];

                    if (!empty($combo['adults'])) {
                    $parts[] = $combo['adults'] . ' adult' . ($combo['adults'] > 1 ? 's' : '');
                    }

                    if (!empty($combo['kids'])) {
                    $parts[] = $combo['kids'] . ' kid' . ($combo['kids'] > 1 ? 's' : '');
                    }

                    return implode(' and ', $parts);
                    });
                    @endphp

                    @if ($formatted->isNotEmpty())
                    <td style=style="padding: 12px; border-bottom: 1px solid #eee;  text-align: center;">
                        {{ $formatted->implode(' or ') }}</td>
                    @endif
                    @endif
                </tr>
                @endif
                @endforeach
            </table>

            {{-- Catering --}}
            @if(!empty($event->dishes) && is_array($event->dishes) && count($event->dishes) > 0)
            <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">
                Catering Dishes</h3>
            <table cellpadding="10" cellspacing="0"
                style="width: 100%; border-collapse: collapse; margin: 15px auto 25px auto;">
                <tr style="background-color: #E8F5E9;">
                    <th align="left" style="padding: 12px; border-bottom: 1px solid #eee;">Dish Name</th>
                </tr>
                @foreach ($event->dishes as $dish)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"> {{ trim($dish) }}</td>
                </tr>
                @endforeach
            </table>
            @endif

            {{-- Activities --}}
            @if (count($activities))
            <h3 style="color: #166534; font-size: 20px; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">
                Add-On Activities</h3>
            <table cellpadding="10" cellspacing="0"
                style="width: 100%; border-collapse: collapse; margin: 15px auto 25px auto;">
                <tr style="background-color: #E8F5E9;">
                    <th align="left" style="padding: 12px; border-bottom: 1px solid #eee;">Activity</th>
                    <th style="padding: 12px; border-bottom: 1px solid #eee;">Quantity</th>
                </tr>
                @foreach ($activities as $activity)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $activity->name }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                        {{ $activity->pivot->quantity }}
                    </td>
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
                </tr>
                @foreach ($services as $service)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $service->name }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                        {{ $service->pivot->quantity ?? 1 }}
                    </td>
                </tr>
                @endforeach
            </table>
            @endif

            {{--
            <hr style="border: none; border-top: 1px solid #cccccc; margin: 30px 0;"> --}}

            <p style="font-size: 16px; margin-top: 30px;">We have also attached a PDF File that contains the full
                details of your event. There’s nothing more you need to do for now. Just get
                ready to enjoy your event!</p>
            <p style="font-size: 16px;">If you have any questions or need assistance before your event, feel
                free to reach out to us.</p>
            <p style="font-size: 18px; font-weight: 600; margin-top: 30px; text-align: center; color: #166534;">
                <strong>See
                    you soon at {{ $branding->company_name }}!</strong>
            </p>
        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">

            <div style="margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 500; margin: 8px 8px;">Connect with us!</span></p>

                <a href="{{ $branding->facebook }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    <img src="{{ asset('images/fb-logo.png') }}" alt="Facebook" style="width: 24px; height: 24px;">

                </a>

                <a href="{{ $branding->instagram }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    <img src="{{ asset('images/ig-logo.png') }}" alt="Instagram" style="width: 24px; height: 24px;">

                </a>

                <a href="https://canopyfarmph.com/guest/homepage" target="_blank"
                    style="margin: 0 8px; text-decoration: none;">
                    {{-- <i class="fa-solid fa-globe" alt="Website"></i> --}}
                    <img src="{{ asset('images/web-logo.png') }}" alt="Website" style="width: 24px; height: 24px;">
                </a>
            </div>

            <div style="font-size: 0.9em; margin-bottom: 15px; line-height: 1.6;">
                <p style="margin: 0;">Phone: <span style="font-weight: 500;">{{ $branding->contact_number }}</span></p>
                <p style="margin: 0;">Address: {{ $branding->address }}</p>
            </div>


            <span style="font-weight: 600; padding-top: 10px; display: block;">&copy; {{ date('Y') }} {{
                $branding->company_name }}. All rights reserved.</span>
        </div>
    </div>
</body>

</html>
