<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f7; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f7; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="480" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #dc2626; padding: 24px 32px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 600;">{{ config('app.name') }}</h1>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="color: #333333; font-size: 16px; margin: 0 0 16px;">Hello <strong>{{ $userName }}</strong>,</p>
                            <p style="color: #555555; font-size: 15px; margin: 0 0 24px; line-height: 1.5;">
                                We received a request to reset your password. Please use the following OTP code to proceed:
                            </p>
                            <div style="text-align: center; margin: 24px 0;">
                                <span style="display: inline-block; background-color: #fef2f2; border: 2px dashed #dc2626; color: #dc2626; font-size: 32px; font-weight: 700; letter-spacing: 8px; padding: 16px 32px; border-radius: 8px;">
                                    {{ $otp }}
                                </span>
                            </div>
                            <p style="color: #777777; font-size: 13px; margin: 24px 0 0; line-height: 1.5; text-align: center;">
                                This code will expire in <strong>10 minutes</strong>. If you did not request a password reset, please ignore this email.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 16px 32px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
