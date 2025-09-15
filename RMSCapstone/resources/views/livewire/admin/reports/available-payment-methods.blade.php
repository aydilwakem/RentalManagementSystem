<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Canopy Farm PH - Audit Logs Summary Report</title>
    <style>
        @page {
            margin: 40px 30px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
            color: #065f46;
        }

        h2 {
            font-size: 19px;
            margin: 8px 0 4px;
            color: #065f46;
        }

        p {
            margin: 0;
            line-height: 1.5;
        }

        .date-range {
            margin-bottom: 5px;
            font-size: 13px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #c0c0c0;
            padding: 8px 10px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            background-color: #e6f7ed;
            color: #065f46;
            font-weight: bold;
            font-size: 12px;
            text-align: left;
        }

        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f0fdf4;
            border: 1px solid #a7f3d0;
            border-radius: 5px;
        }

        .summary p {
            margin: 5px 0;
            font-size: 13px;
        }

        .summary p strong {
            color: #047857;
        }

        footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #888;
        }

        .page-number {
            position: fixed;
            top: 30px;
            right: 40px;
            font-size: 11px;
            color: #666;
        }

        .page-number:after {
            content: "Page " counter(page);
        }

        /* Style for cards */
        .cards-wrapper {
            text-align: center;

        }

        .card {
            display: inline-block;
            vertical-align: top;
            width: 220px;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .card p {
            margin: 4px 0;
        }

        .container {
            padding: 8px 12px;
        }

        .qr {
            margin-top: 10px;
            text-align: center;
        }

        .qr img {
            width: 200px;
            height: 200px;
            display: block;
            margin: 0 auto;
        }

        .no-qr {
            color: #999;
            font-style: italic;
        }

        .mode {
            font-size: 16px;
            font-weight: bold;
            margin-top: 6px;
        }

        .intro-text {
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <header>
        <div class="page-number"></div>
        <img src="{{ public_path('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 50px;">
        <h1>Canopy Farm PH</h1>
        <p>006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines</p>
        <p>+63 962 447 9893</p>
        <h2>Available Payment Methods</h2>


        <div class="intro-text">
            <strong style="font-size: 16px;">Prefer to pay manually?</strong><br>
            You may use any of the following options and upload your screenshot or deposit slip as proof of payment
            through the link below:
            <br>
            <a href="https://larabelles-rms.com/guest/proof-of-payment-page" target="_blank"
                style="color: #007bff; text-decoration: underline;">
                Upload Proof of Payment
            </a>:
            <br><br>
        </div>

        <div class="cards-wrapper">
            @foreach($paymentMethods as $paymentMethod)
            <div class="card">
                @if($paymentMethod['qr_image'])
                <div class="qr">
                    <img src="{{ public_path('storage/' .$paymentMethod['qr_image']) }}" alt="QR Code">
                </div>
                @else
                <p class="no-qr">No QR Image Available</p>
                @endif

                <div class="container">
                    <p class="mode"><strong>{{ $paymentMethod['mode_of_payment_name'] }}</strong></p>
                    <p>{{ $paymentMethod['account_name'] }}</p>
                    <p>{{ $paymentMethod['account_number'] }}</p>
                </div>
            </div>
            @endforeach
        </div>


        <footer>
            <div class="page-number"></div>
            &copy; {{ now()->year }} Canopy Farm PH &mdash; Report generated on {{ now()->format('F d, Y h:i A') }}
        </footer>
</body>

</html>