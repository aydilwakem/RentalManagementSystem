<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmation</title>

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

<body style="background-color: #f8f8f8; font-family: Poppins, sans-serif; margin: 0; padding: 0; color: #333;">

    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">

        {{-- Header --}}
        <div
            style="background-color: #166534; color: #fff; padding: 25px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; text-align: center;">
            <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px;">
                Quote Request Received!
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">We've successfully received your event quote request and
                will get back to you soon.
            </p>
        </div>

        {{-- Content --}}
        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">

            <p style="margin-bottom: 15px;"><strong>Hello {{ $quoteData['contact_person'] }},</strong></p>

            <p style="margin-bottom: 20px;">
                Thank you for your interest in Canopy Farm PH for your upcoming event! We've successfully received your
                quote request and are thrilled at the opportunity to help make your special occasion memorable.
            </p>

            <p style="margin-bottom: 20px;">
                Our dedicated event managers are now reviewing your request and will be reaching out to you shortly to
                discuss the details and provide a personalized quote. You can expect a response within 1-2 business days.
            </p>

            <p style="margin-bottom: 15px;">For your records, here is a summary of your request:</p>

            {{-- Contact Details --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0; margin-bottom: 10px;">Contact
                    Information</h3>
                <ul style="padding-left: 0; list-style: none; margin: 0;">
                    @if (!empty($quoteData['company_name']))
                        <li style="margin-bottom: 8px;"><strong>Company:</strong> {{ $quoteData['company_name'] }}</li>
                    @endif
                    <li style="margin-bottom: 8px;"><strong>Contact Person:</strong> {{ $quoteData['contact_person'] }}
                    </li>
                    <li style="margin-bottom: 8px;"><strong>Email:</strong> {{ $quoteData['email'] }}</li>
                    <li style="margin-bottom: 8px;"><strong>Contact Number:</strong> {{ $quoteData['contact_number'] }}</li>
                </ul>
            </div>

            {{-- Event Details --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; color: #166534; margin-top: 0; margin-bottom: 10px;">Event Details</h3>
                <ul style="padding-left: 0; list-style: none; margin: 0;">
                    <li style="margin-bottom: 8px;"><strong>Selected Event Hall:</strong>
                        {{ $quoteData['selected_hall']->name_number }}</li>
                    <li style="margin-bottom: 8px;"><strong>Event Start:</strong> <strong
                            style="color: #166534;">{{ \Carbon\Carbon::parse($quoteData['event_start'])->format('F j, Y \a\t h:i A') }}</strong>
                    </li>
                    <li style="margin-bottom: 8px;"><strong>Event End:</strong> <strong
                            style="color: #166534;">{{ \Carbon\Carbon::parse($quoteData['event_end'])->format('F j, Y \a\t h:i A') }}</strong>
                    </li>
                    <li style="margin-bottom: 8px;"><strong>Event Type:</strong>
                        {{ $quoteData['event_type'] ?? $quoteData['other_event_type'] }}</li>
                </ul>
            </div>

            {{-- Additional Requests / Comments --}}
            @if (!empty($quoteData['additional_requests']))
                <p style="margin-bottom: 10px; font-weight: bold; color: #166534;">Additional Requests/Comments:</p>
                <div
                    style="
                    background-color: #ffffff;
                    border: 1px solid #cccccc;
                    border-left: 5px solid #166534;
                    border-radius: 5px;
                    padding: 15px;
                    margin-top: 5px;
                    margin-bottom: 20px;
                    word-wrap: break-word;
                    line-height: 1.5;
                    color: #333333;">
                    {{ $quoteData['additional_requests'] }}
                </div>
            @endif

            {{-- Closing statement and contact --}}
            <p style="margin-top: 25px;">
                If you have any urgent questions before we get back to you, don't hesitate to call us at <span style="color:#166534; font-weight: 700;">0962-447-9893</span> We look forward to helping you plan your perfect event!<br>
            </p>

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
