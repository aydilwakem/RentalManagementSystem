<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Official Receipt</title>
</head>

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

<body style="background-color: #f8f8f8; font-family: Poppins, sans-serif; margin: 0; padding: 0; color: #333;">
    <div
        style="max-width: 800px; margin: 30px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        {{-- Header --}}
        <div
            style="background-color: #166534; color: #fff; padding: 25px 30px; border-top-left-radius: 8px; border-top-right-radius: 8px; text-align: center;">

            <img src="{{ asset('storage/' . $logo_path) }}" alt="{{ $branding_company_name }}"
                style="max-height: 50px;">
            <h1 style="font-size: 28px; font-weight: 700; margin: 0; padding-top: 10px; color:#fff;">
                Transaction Completed!
            </h1>
            <p style="font-size: 16px; margin: 10px 0 20px; color:#fff;">Your official receipt is attached for your
                records.
            </p>
        </div>

        <div style="padding: 20px 30px; font-size: 16px; line-height: 1.6;">

            <p style="margin-bottom: 15px;"><strong>Dear
                    {{ $transactionUser->first_name ?? 'Customer' }}
                    {{ $transactionUser->last_name ?? 'Last Name' }},
                </strong>
            </p>

            <p style="margin-bottom: 20px;">Thank you for your recent transaction with {{ $branding_company_name }}!
                Please find your official
                receipt attached as a PDF for your reference.</p>

            <p style="color:#166534"><strong>Receipt Number:</strong>
                {{ $receipt->receipt_number }}</p>
            </p>

            <p>If you have any questions or need further assistance, please don't hesitate
                to contact us.</p>

            <p style="margin-top: 20px;">Best regards,<br>
                {{ $branding_company_name }}
            </p>
        </div>

        {{-- Footer --}}
        <div
            style="background-color: #166534; color: #fff; text-align: center; padding: 15px; font-size: 14px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">

            <div style="margin-bottom: 10px;">
                <p style="margin: 0; font-weight: 500; margin: 8px 8px; color:#fff;">Connect with us!</span></p>

                <a href="{{ $facebook_link }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    <img src="{{ asset('images/fb-logo.png') }}" alt="Facebook" style="width: 24px; height: 24px;">
                </a>

                <a href="{{ $instagram_link }}" target="_blank" style="margin: 0 8px; text-decoration: none;">
                    <img src="{{ asset('images/ig-logo.png') }}" alt="Instagram" style="width: 24px; height: 24px;">
                </a>

                <a href="https://larabelles-rms.com/guest/homepage" target="_blank"
                    style="margin: 0 8px; text-decoration: none;">
                    <img src="{{ asset('images/web-logo.png') }}" alt="Website" style="width: 24px; height: 24px;">
                </a>
            </div>

            <div style="font-size: 0.9em; margin-bottom: 15px; line-height: 1.6; color:#fff;">
                <p style="margin: 0; color:#fff;">Phone: <span style="font-weight: 500;">
                        {{ $branding_company_contact }}
                    </span>
                </p>
                <p style="margin: 0; color:#fff;">Address:
                    {{ $company_address }}
                </p>
            </div>


            <span style="font-weight: 600; padding-top: 10px; display: block; color:#fff;">&copy; {{ date('Y') }}
                {{ $branding_company_name }}.
                All rights reserved.</span>

        </div>
    </div>
</body>

</html>