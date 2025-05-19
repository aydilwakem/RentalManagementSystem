<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    body,
    p,
    h3,
    td {
        font-family: 'Poppins', sans-serif;
    }
</style>

<body style="background-color: #f8f8f8; font-family: Poppins, sans-serif; margin: 0; padding: 0; color: #333;">
    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        <div
            style="background-color: #166534; color: #fff; padding: 20px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <img src="{{ asset('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 60px;">
            </div>
            <h1 style="font-size: 24px; font-weight: bold; text-align: center; flex-grow: 1; margin: 0;">
                Copy of Contact Us Response
            </h1>
            <div></div>
        </div>


        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">

            <p><strong>Good day, {{ $contactData['name'] }}</strong></p>

            <p> Thank you for reaching out to Canopy Farm PH!
            </p>

            <p>
                We truly appreciate you for reaching out to us! Your message has been sent and our dedicated admins will
                contact you for any immediate concerns.</p>


            <p>Here is the summary of message:</p>

            <ul style="padding-left: 20px; margin-top: 10px;">
                <li style="margin-bottom: 8px;"><strong>Name:</strong> {{ $contactData['name'] }}</li>
                <li style="margin-bottom: 8px;"><strong>Email:</strong> {{ $contactData['email'] }}</li>
                <li style="margin-bottom: 8px;"><strong>Contact Number:</strong> {{ $contactData['contact_number'] }}
                </li>
                <li style="margin-bottom: 8px;"><strong>Message:</strong>
                    {{ $contactData['message'] }}</li>
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