<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UB Sync account verification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <h2>Verify your UB Sync account</h2>
    <p>Hello {{ $recipientName }},</p>
    <p>Use the verification code below to complete your account creation:</p>
    <p style="font-size: 28px; font-weight: bold; letter-spacing: 8px; color: #800000;">
        {{ $code }}
    </p>
    <p>This code will expire in <strong>1 minute</strong>. If you did not request this, you can safely ignore this email.</p>
    <p>Thank you,<br>UB Sync</p>
</body>
</html>
