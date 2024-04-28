<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif;">

<table style="max-width: 600px; margin: 0 auto; padding: 20px; border-collapse: collapse;">
    <tr>
        <td style="background-color: #ffffff; padding: 40px; text-align: center;">
            <h2 style="margin-bottom: 20px;">Email Verification</h2>
            <p style="margin-bottom: 20px;">Hello,</p>
            <p style="margin-bottom: 20px;">Thank you for signing up. To complete your registration, please use the verification code below:</p>
            <p style="margin-bottom: 20px; font-size: 24px; font-weight: bold;">{{ $verificationCode }}</p>
            <p style="margin-bottom: 20px;">If you did not request this verification, please ignore this email.</p>
        </td>
    </tr>
    <tr>
        <td style="background-color: #f9f9f9; padding: 20px; text-align: center;">
            <p style="margin: 0;">This email was sent to you because you signed up for our service. If you have any questions, please contact us.</p>
        </td>
    </tr>
</table>

</body>
</html>
