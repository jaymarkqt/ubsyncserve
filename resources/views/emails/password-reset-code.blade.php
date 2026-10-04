<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UB Sync password reset</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <h2>Reset your UB Sync password</h2>
    <p>Hello {{ $recipientName }},</p>
    <p>Use this 6-digit verification code to reset your password:</p>
    <p style="font-size: 28px; font-weight: bold; letter-spacing: 8px; color: #800000;">
        {{ $code }}
    </p>
    <p>This code expires in <strong>1 minute</strong>. If you did not request a password reset, you can safely ignore this email.</p>
    <p>Thank you,<br>UB Sync</p>
</body>
</html>
