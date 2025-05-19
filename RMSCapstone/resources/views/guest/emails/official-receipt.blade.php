<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Official Receipt</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #2d3748;">Your Official Receipt from Canopy Farm</h2>

    <p>Dear {{ $transactionUser->first_name ?? 'Customer' }},</p>

    <p>Thank you for your recent transaction with us. Please find your official receipt attached as a PDF.</p>

    <p><strong>Receipt Number:</strong> {{ $receiptNumber }}</p>
    <p>If you have any questions or need further assistance, please don't hesitate to contact us.</p>

    <br>
    <p>Best regards,<br>
        Canopy Farm Team</p>
</body>

</html>