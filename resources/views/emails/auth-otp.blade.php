<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'registration' ? 'Verify Your Email' : 'Your Login Security Code' }}</title>
    <style>
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse !important;
        }
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                width: 100% !important;
                padding: 10px !important;
            }
            .email-container {
                width: 100% !important;
                border-radius: 8px !important;
            }
            .header-cell {
                padding: 24px 20px !important;
            }
            .body-cell {
                padding: 24px 18px !important;
            }
            .otp-code {
                font-size: 32px !important;
                letter-spacing: 6px !important;
            }
        }
    </style>
</head>
<body style="background-color: #f1f5f9; margin: 0; padding: 24px 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <center>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-wrapper" style="max-width: 540px; margin: 0 auto;">
            <tr>
                <td>
                    <!-- Main Container -->
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
                        
                        <!-- Top Accent Bar -->
                        <tr>
                            <td height="4" style="background-color: #0da487; font-size: 0; line-height: 0;">&nbsp;</td>
                        </tr>

                        <!-- Header -->
                        <tr>
                            <td class="header-cell" style="background-color: #0f172a; padding: 30px; text-align: center;">
                                <div style="font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: 2px;">
                                    KENKIE
                                </div>
                                <div style="font-size: 13px; color: #34d399; margin-top: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px;">
                                    {{ $type === 'registration' ? 'Email Verification' : 'Secure Login Verification' }}
                                </div>
                            </td>
                        </tr>

                        <!-- Body -->
                        <tr>
                            <td class="body-cell" style="padding: 32px 30px;">
                                
                                <p style="font-size: 16px; margin: 0 0 14px 0; color: #0f172a; line-height: 1.5;">
                                    Hello <strong>{{ $userName ?: 'Valued Customer' }}</strong>,
                                </p>
                                
                                <p style="font-size: 14px; margin: 0 0 24px 0; color: #475569; line-height: 1.6;">
                                    @if ($type === 'registration')
                                        Thank you for creating an account with Kenkie! To complete your registration and activate your account, please enter the 6-digit verification code below:
                                    @else
                                        A sign-in request was received for your Kenkie account. Use the one-time verification code below to securely authenticate:
                                    @endif
                                </p>

                                <!-- Code Box -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0fdf4; border: 2px dashed #0da487; border-radius: 10px; margin: 20px 0 24px 0;">
                                    <tr>
                                        <td align="center" style="padding: 22px 16px;">
                                            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #166534; letter-spacing: 1px; margin-bottom: 6px;">
                                                Verification Security Code
                                            </div>
                                            <div class="otp-code" style="font-family: 'Courier New', Courier, monospace, monospace; font-size: 38px; font-weight: 800; letter-spacing: 8px; color: #0da487; padding-left: 8px;">
                                                {{ $code }}
                                            </div>
                                            <div style="font-size: 12.5px; color: #64748b; margin-top: 8px;">
                                                ⏱️ Code expires in <strong style="color: #0f172a;">10 minutes</strong>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <p style="font-size: 13.5px; margin: 0 0 18px 0; color: #64748b; line-height: 1.6;">
                                    If you did not make this request, you can safely ignore this email. No action will be taken on your account without this code.
                                </p>

                                <!-- Security Advisory Card -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 14px;">
                                    <tr>
                                        <td style="padding: 12px 16px; font-size: 12.5px; color: #64748b; line-height: 1.5;">
                                            🔒 <strong>Security Tip:</strong> Never share this code with anyone. Kenkie staff will never ask for your password or verification code.
                                        </td>
                                    </tr>
                                </table>

                                <!-- Support Link -->
                                <div style="margin-top: 24px; text-align: center; font-size: 13px; color: #64748b;">
                                    Need assistance? Contact us at <a href="mailto:info@kenkie.com" style="color: #0da487; font-weight: 700; text-decoration: none;">info@kenkie.com</a>
                                </div>

                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 24px; text-align: center; font-size: 12px; color: #94a3b8; line-height: 1.6;">
                                &copy; {{ date('Y') }} <strong>Kenkie Ltd</strong>. Walsall, WS2 9AD, United Kingdom.<br>
                                All rights reserved &bull; <a href="{{ url('/') }}" style="color: #0da487; text-decoration: none;">{{ url('/') }}</a>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
