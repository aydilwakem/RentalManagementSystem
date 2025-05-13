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
            style="background-color: #166534; color: #fff; padding: 25px; text-align: center; font-size: 24px; font-weight: bold; border-top-left-radius: 8px; border-top-right-radius: 8px;">
            New Event Quote Request
        </div>


        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">

            <p><strong>Good day, Event Manager</strong></p>

            <p> Below are the details of a new event quote request from {{ $quoteData['company_name'] }}, submitted by
                {{ $quoteData['contact_person'] }}. Please review the information and coordinate with the client for
                further discussion and confirmation.
            </p>

            <p>Here is the summary of the event quote:</p>

            <ul style="padding-left: 20px; margin-top: 10px;">
                <li style="margin-bottom: 8px;"><strong>Company:</strong> {{ $quoteData['company_name'] }}</li>
                <li style="margin-bottom: 8px;"><strong>Contact Person:</strong> {{ $quoteData['contact_person'] }}</li>
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