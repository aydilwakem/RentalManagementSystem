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
</head>

<body style="background-color: #f8f8f8; font-family: Poppins, sans-serif; margin: 0; padding: 0; color: #333;">

    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        <div
            style="background-color: #166534; color: #fff; padding: 20px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 60px;">
            </div>
            <h1 style="font-size: 24px; font-weight: bold; text-align: center; flex-grow: 1; margin: 0;">
                Quote Request Received
            </h1>
            <div></div>
        </div>


        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3>Thank you for reaching out to us!</h3>

            <p>Good day {{ $quoteData['contact_person'] }},</p>

            <p>Thank you for considering <strong> Canopy Farm PH</strong> as the venue for your upcoming event! We’re
                thrilled at the opportunity to be part of your special occasion. </p>

            <p>
                We truly appreciate you choosing Canopy Farm PH. Your request has been received and is currently under
                review. One of our dedicated event managers will be reaching out to you shortly to discuss the details
                and provide a confirmation.</p>

            <p> If you have any questions in the meantime, feel free to contact us. We look forward to creating a
                memorable experience with you!
            </p>

            <p>Here is a summary of your request:</p>

            <ul style="padding-left: 20px; margin-top: 10px;">
                <li style="margin-bottom: 8px;"><strong>Company:</strong> {{ $quoteData['company_name'] }}</li>
                <li style="margin-bottom: 8px;"><strong>Company:</strong> {{ $quoteData['contact_person'] }}</li>
                <li style="margin-bottom: 8px;"><strong>Email:</strong> {{ $quoteData['email'] }}</li>
                <li style="margin-bottom: 8px;"><strong>Contact Number:</strong> {{ $quoteData['contact_number'] }}</li>
                <li style="margin-bottom: 8px;"><strong>Selected Event Hall: </strong>{{
                    $quoteData['selected_hall']->name_number }}</li>
                <li style="margin-bottom: 8px;"><strong>Event Start:</strong> {{
                    \Carbon\Carbon::parse($quoteData['event_start'])->format('F j, Y \a\t h:i A') }}</li>
                <li style="margin-bottom: 8px;"><strong>Event End:</strong> {{
                    \Carbon\Carbon::parse($quoteData['event_end'])->format('F j, Y \a\t h:i A') }}</li>
                <li style="margin-bottom: 8px;"><strong>Event Type:</strong> {{ $quoteData['event_type'] ??
                    $quoteData['other_event_type'] }}</li>
                <li style="margin-bottom: 8px;"><strong>Additional Requests:</strong> {{
                    $quoteData['additional_requests'] }}</li>
            </ul>

        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            &copy; {{ date('Y') }} Canopy Farm PH. All rights reserved.
        </div>
    </div>

</body>

</html>
