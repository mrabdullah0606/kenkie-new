<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration Alert</title>
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
            .btn-action {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
                text-align: center !important;
                padding: 14px 18px !important;
            }
        }
    </style>
</head>
<body style="background-color: #f1f5f9; margin: 0; padding: 24px 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <center>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-wrapper" style="max-width: 580px; margin: 0 auto;">
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
                            <td class="header-cell" style="background-color: #0f172a; padding: 26px 30px;">
                                <div style="display: inline-block; background-color: rgba(13, 164, 135, 0.2); border: 1px solid rgba(13, 164, 135, 0.4); border-radius: 4px; padding: 3px 8px; font-size: 11px; font-weight: 700; color: #34d399; letter-spacing: 1px; text-transform: uppercase;">
                                    Kenkie &bull; Admin Alert
                                </div>
                                <h1 style="margin: 8px 0 0 0; font-size: 20px; font-weight: 800; color: #ffffff; letter-spacing: -0.3px;">
                                    New Customer Registration
                                </h1>
                            </td>
                        </tr>

                        <!-- Body -->
                        <tr>
                            <td class="body-cell" style="padding: 28px 30px;">
                                
                                <p style="font-size: 14.5px; margin: 0 0 20px 0; color: #334155; line-height: 1.6;">
                                    A new customer has just created and verified an account on your online store:
                                </p>

                                <!-- Customer Details Card -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px;">
                                    <tr>
                                        <td style="padding: 18px 20px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                                                👤 Customer Profile
                                            </div>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13.5px; line-height: 1.6; color: #334155;">
                                                <tr>
                                                    <td width="36%" style="color: #64748b; font-weight: 500; padding: 3px 0;">Customer Name:</td>
                                                    <td style="font-weight: 700; color: #0f172a; padding: 3px 0; word-break: break-word;">{{ $user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 3px 0;">Email Address:</td>
                                                    <td style="padding: 3px 0; word-break: break-all;">
                                                        <a href="mailto:{{ $user->email }}" style="color: #0da487; text-decoration: none; font-weight: 600;">{{ $user->email }}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 3px 0;">Account Role:</td>
                                                    <td style="color: #0f172a; padding: 3px 0; text-transform: capitalize;">{{ $user->role ?: 'Customer' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 3px 0;">Registration Date:</td>
                                                    <td style="color: #0f172a; padding: 3px 0;">{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 3px 0;">Sign-up IP:</td>
                                                    <td style="color: #64748b; font-family: monospace; font-size: 12px; padding: 3px 0;">{{ $ipAddress ?: request()->ip() ?: 'Not recorded' }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Action Button -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td align="center" style="padding-top: 6px; padding-bottom: 10px;">
                                            <a href="{{ route('admin.users.index') }}" class="btn-action" style="display: inline-block; background-color: #0da487; color: #ffffff !important; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 28px; border-radius: 8px; box-shadow: 0 2px 6px rgba(13, 164, 135, 0.35);">
                                                View Users in Admin Panel &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 24px; text-align: center; font-size: 12px; color: #94a3b8; line-height: 1.6;">
                                Automated store notification from <strong>Kenkie</strong> &bull; Walsall, United Kingdom<br>
                                Store Management System &bull; <a href="{{ url('/') }}" style="color: #0da487; text-decoration: none;">{{ url('/') }}</a>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
