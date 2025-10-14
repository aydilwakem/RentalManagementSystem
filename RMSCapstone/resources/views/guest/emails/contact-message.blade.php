<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Us Message</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
    body,
    p,
    h3,
    td {
        font-family: 'Poppins', sans-serif;
    }
</style>

<body style="margin: 0; padding: 0; background-color: #f0f0f0; font-family: Poppins, sans-serif; color: #333333;">

    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">

        {{-- Header --}}
        <div
            style="background-color: #166534; color: #fff; padding: 25px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; text-align: center;">
            <img src="{{ asset('storage/' .   $contactData['logo_path'] ) }}" alt=" {{ $contactData['company_name'] }}"
                style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px;">
                New Message from Contact Us!
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px;">A new inquiry awaits your attention.</p>
        </div>

        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">

            <p style="margin-bottom: 15px;"><strong>Good day, {{ $contactData['company_name'] ?? 'No Name'}}
                    Admins!</strong></p>

            <p style="margin-bottom: 20px;">
                Below are the details of a new message from <strong> {{ $contactData['name'] }}</strong>, submitted
                through the
                Contact Us
                form. Please review the information and coordinate with the guest for further discussion.
            </p>

            <p style="margin-bottom: 10px;">Here is the summary of the message:</p>

            {{-- Contact Details Section --}}
            <div
                style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <ul style="padding-left: 0; list-style: none; margin: 0;">
                    <li style="margin-bottom: 8px;"><strong>Name: </strong> {{ $contactData['name'] }}</li>
                    <li style="margin-bottom: 8px;"><strong>Email:</strong> <a href="mailto:{{ $contactData['email'] }}"
                            style="color: #007bff; text-decoration: none;">{{ $contactData['email'] }}</a></li>
                    <li style="margin-bottom: 8px;"><strong>Contact Number:</strong>
                        {{ $contactData['contact_number'] }}</li>
                </ul>
            </div>

            {{-- Message Box Section --}}
            <p style="margin-bottom: 10px; font-weight: bold;">Message:</p>
            <div style="
                background-color: #ffffff;
                border: 1px solid #cccccc;
                border-left: 5px solid #166534;
                border-radius: 5px;
                padding: 15px;
                margin-top: 5px;
                font-family: monospace;
                word-wrap: break-word;
                line-height: 1.5;
                color: #333333;">
                {{ $contactData['message'] }}
            </div>

        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">

            <div style="margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 500; margin: 8px 8px;">Connect with us!</span></p>

                <a href="{{ $facebook_link }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    <i class="fa-brands fa-facebook" alt="Facebook"></i>
                    {{-- <img src="{{ asset('images/fb-logo.png') }}" alt="Facebook" style="width: 24px; height: 24px;"> --}}
                </a>

                <a href="{{ $instagram_link }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    <i class="fa-brands fa-instagram" alt="Instagram"></i>
                    {{-- <img src="{{ asset('images/ig-logo.png') }}" alt="Instagram" style="width: 24px; height: 24px;"> --}}
                </a>

                <a href="https://canopyfarmph.com/guest/homepage" target="_blank"
                    style="margin: 0 8px; text-decoration: none;">
                    <i class="fa-solid fa-globe" alt="Website"></i>
                    {{-- <img src="{{ asset('images/web-logo.png') }}" alt="Website" style="width: 24px; height: 24px;"> --}}
                </a>
            </div>

            <div style="font-size: 0.9em; margin-bottom: 15px; line-height: 1.6; color:#fff;">
                <p style="margin: 0;">Phone: <span style="font-weight: 500;">{{ $contactData['company_contact'] ?? 'No
                        Contact
                        Number'}}</span></p>
                <p style="margin: 0;">Address: <span style="font-weight: 500;">{{ $contactData['company_address'] ?? 'No
                        Address
                        '}}</span></p>
            </div>


            <span style="font-weight: 600; padding-top: 10px; display: block; color:#fff;">&copy; {{ date('Y') }}
                {{$contactData['company_name'] }}. All rights reserved.</span>

        </div>
    </div>
</body>

</html>
