<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt Rejected</title>

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
                Payment Receipt Rejected
            </h1>
            <div></div>
        </div>

        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">
            <h3>Dear {{ $first_name }} {{ $last_name }},</h3>

            <p style="color: #ef4444;"><strong>Rejection Reason: {{ $rejection_reason }} </strong> </p>
            <p>We regret to inform you that your submitted payment receipt has been rejected
                following a thorough review by our
                team due to <strong style="color: #ef4444;">{{ lcfirst($rejection_reason) }}.</strong>
                Unfortunately, it
                did not meet
                the required
                verification criteria.
                For transparency, we have
                provided
                the
                specific reason for the rejection above so you can take the necessary steps to address the issue and
                proceed
                with your reservation</p>

            <p>If you have any questions, need further clarification, or require assistance, please don’t hesitate to
                contact
                us. We’re here to help!</p>

            <p>Best regards,<br>Support Team, Canopy Farm PH</p>
        </div>

        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            &copy; {{ date('Y') }} Canopy Farm PH. All rights reserved.
        </div>
    </div>
</body>

</html>
