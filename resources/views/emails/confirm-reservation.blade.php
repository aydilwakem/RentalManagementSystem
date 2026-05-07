<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservation Confirmation</title>
    <style>
        body {
            background-color: #f8f8f8;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #166534;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .reservation-id {
            text-align: center;
            margin: 20px 0;
            font-size: 36px;
            font-weight: bold;
            color: #166534;
        }

        .greeting {
            padding: 10px 20px 20px 20px;
            text-align: center;
            font-size: 18px;
        }

        .details-wrapper {
            padding: 0 20px 20px 20px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .details-table th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .breakdown-table th,
        .breakdown-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .breakdown-table th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        .computation {
            margin: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 4px;
            font-size: 16px;
        }

        .computation-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .computation-item:last-child {
            margin-bottom: 0;
        }

        .footer {
            background-color: #166534;
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            Reservation Confirmed
        </div>
        <!-- Reservation ID -->
        <div class="reservation-id">
            Reservation ID: #123456
        </div>
        <!-- Greeting -->
        <div class="greeting">
            <p>Dear [Customer Name],</p>
            <p>Your reservation has been confirmed. Please review your reservation details below.</p>
        </div>
        <!-- Details Table Wrapper -->
        <div class="details-wrapper">
            <table class="details-table">
                <tr>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Pax</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>11/09/2024</td>
                    <td>11/10/2024</td>
                    <td>2</td>
                    <td>PHP 2,440.00</td>
                </tr>
            </table>
            <br>
            <p><strong>Breakdown of fees:</strong></p>
            <div class="computation">
                <div class="computation-item">
                    <span class="label">Room</span>
                    <span class="value">PHP 920.00</span>
                </div>
                <div class="computation-item">
                    <span class="label">Activity</span>
                    <span class="value">PHP 300.00</span>
                </div>
                <div class="computation-item">
                    <span class="label">VAT</span>
                    <span class="value">PHP 150.00</span>
                </div>
                <div class="computation-item">
                    <span class="label">Amount Paid</span>
                    <span class="value">PHP 1,220.00</span>
                </div>
                <div class="computation-item">
                    <span class="label">Grand Total</span>
                    <span class="value">PHP 2,440.00</span>
                </div>
                <div class="computation-item">
                    <span class="label">Balance Due</span>
                    <span class="value">PHP 1,220.00</span>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            &copy; 2025 Canopy Farm. All rights reserved.
        </div>
    </div>
</body>

</html>
