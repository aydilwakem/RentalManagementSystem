<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Day Tour Reservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 150px;
            height: auto;
        }

        .reservation-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
        }

        .detail-value {
            text-align: right;
        }

        .total-section {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            color: #666;
            font-size: 14px;
        }

        .social-links {
            margin: 15px 0;
        }

        .social-links a {
            margin: 0 10px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if($logo_path)
                <img src="{{ $logo_path }}" alt="{{ $branding_company_name }}" class="logo">
            @endif
            <h1>{{ $branding_company_name }}</h1>
            <p>New Day Tour Reservation</p>
        </div>

        <!-- Greeting -->
        <div style="margin-bottom: 30px;">
            <h2>Hello {{ $name }},</h2>
            <p>Thank you for your day tour reservation! Here are your reservation details:</p>
        </div>

        <!-- Reservation Details -->
        <div class="reservation-details">
            <h3 style="margin-top: 0; color: #2c5aa0;">Reservation Information</h3>

            <div class="detail-row">
                <span class="detail-label">Transaction Number:</span>
                <span class="detail-value"><strong>{{ $transaction_number }}</strong></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Invoice Number:</span>
                <span class="detail-value">{{ $invoice_number }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Tour Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($tour_date)->format('F j, Y') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Tour Name:</span>
                <span class="detail-value">{{ $tour_name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Rate Package:</span>
                <span class="detail-value">{{ $rate_name }}</span>
            </div>
        </div>

        <!-- Guest Count -->
        <div style="background: #f0f8ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #2c5aa0;">Guest Information</h4>
            <div style="display: flex; justify-content: space-around; text-align: center;">
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #2c5aa0;">{{ $adult_count }}</div>
                    <div>Adults</div>
                </div>
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #2c5aa0;">{{ $kid_count }}</div>
                    <div>Kids</div>
                </div>
                <div>
                    <div style="font-size: 24px; font-weight: bold; color: #2c5aa0;">{{ $adult_count + $kid_count }}
                    </div>
                    <div>Total Guests</div>
                </div>
            </div>
        </div>

        <!-- Pricing Breakdown -->
        <div style="margin: 20px 0;">
            <h4 style="color: #2c5aa0;">Pricing Breakdown</h4>

            <div class="detail-row">
                <span class="detail-label">Adults ({{ $adult_count }} × ₱{{ number_format($adult_rate, 2) }}):</span>
                <span class="detail-value">₱{{ number_format($adult_count * $adult_rate, 2) }}</span>
            </div>

            @if($kid_count > 0)
                <div class="detail-row">
                    <span class="detail-label">Kids ({{ $kid_count }} × ₱{{ number_format($kid_rate, 2) }}):</span>
                    <span class="detail-value">₱{{ number_format($kid_count * $kid_rate, 2) }}</span>
                </div>
            @endif

            @if($convenience_fee > 0)
                <div class="detail-row">
                    <span class="detail-label">Convenience Fee:</span>
                    <span class="detail-value">₱{{ number_format($convenience_fee, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Total Amount -->
        <div class="total-section">
            <div class="detail-row" style="border-bottom: none; font-size: 18px;">
                <span class="detail-label" style="font-size: 18px;">Total Amount:</span>
                <span class="detail-value" style="font-size: 18px; font-weight: bold;">
                    ₱{{ number_format($total_amount, 2) }}
                </span>
            </div>
        </div>

        <!-- Payment Information -->
        @if($payment_link)
            <div
                style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
                <h4 style="margin-top: 0; color: #856404;">Payment Instructions</h4>
                <p>You can complete your payment using the link below:</p>
                <a href="{{ $payment_link }}"
                    style="display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 10px 0;">
                    Pay Now
                </a>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <h4>Contact Information</h4>
            <p>{{ $branding_company_name }}</p>
            <p>{{ $company_address }}</p>
            <p>Email: {{ $branding_company_email }} | Phone: {{ $branding_company_contact }}</p>

            <div class="social-links">
                @if($facebook_link)
                    <a href="{{ $facebook_link }}">Facebook</a>
                @endif
                @if($instagram_link)
                    <a href="{{ $instagram_link }}">Instagram</a>
                @endif
            </div>

            <p style="font-size: 12px; color: #999;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>

</html>