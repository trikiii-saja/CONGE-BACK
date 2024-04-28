<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
</head>
<body style="font-family: Arial, sans-serif;">

<table style="max-width: 600px; margin: 0 auto; padding: 20px; border-collapse: collapse;">
    <tr>
        <td style="background-color: #ffffff; padding: 40px; text-align: center;">
            <h2 style="margin-bottom: 20px;">Password Reset Code</h2>
            <p style="margin-bottom: 20px;">Hello,</p>
            <p style="margin-bottom: 20px;">You have requested to reset your password. Please use the verification code below to proceed:</p>
            <p style="margin-bottom: 20px; font-size: 24px; font-weight: bold;">{{ $verificationCode }}</p>
            <p style="margin-bottom: 20px;">If you did not request this password reset, please ignore this email.</p>
        </td>
    </tr>
    <tr>
        <td style="background-color: #f9f9f9; padding: 20px; text-align: center;">
            <p style="margin: 0;">This email was sent to you because you requested a password reset. If you have any questions, please contact us.</p>
        </td>
    </tr>
</table>

</body>
</html>
