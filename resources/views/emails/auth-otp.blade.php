<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'registration' ? 'Verify Your Email' : 'Your Login Security Code' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 24px;
            color: #333333;
        }
        .email-container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }
        .email-header {
            background-color: #0da487;
            padding: 30px 24px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .email-body {
            padding: 36px 32px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #111827;
        }
        .code-box {
            background-color: #f0fdf4;
            border: 2px dashed #0da487;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 28px 0;
        }
        .code-number {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #0da487;
            font-family: 'Courier New', Courier, monospace;
            display: inline-block;
        }
        .code-hint {
            font-size: 13px;
            color: #4b5563;
            margin-top: 8px;
        }
        .warning-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 24px;
            border-top: 1px solid #f3f4f6;
            padding-top: 16px;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 20px 32px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>KENKIE</h1>
        </div>
        <div class="email-body">
            <div class="greeting">Hello {{ $userName ?: 'Customer' }},</div>
            <p>
                @if ($type === 'registration')
                    Thank you for signing up with Kenkie! To complete your account registration and verify your email address, please enter the one-time verification code below:
                @else
                    You recently requested to sign in to your Kenkie account. Please enter the verification security code below to complete your login:
                @endif
            </p>

            <div class="code-box">
                <div class="code-number">{{ $code }}</div>
                <div class="code-hint">This code expires in <strong>10 minutes</strong>.</div>
            </div>

            <p style="font-size: 14px; color: #4b5563;">
                If you did not request this code, please ignore this email or contact our support team at <a href="mailto:info@kenkie.com" style="color: #0da487;">info@kenkie.com</a>.
            </p>

            <div class="warning-text">
                For security reasons, never share this code with anyone. Kenkie representatives will never ask for your verification code.
            </div>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Kenkie Ltd. Walsall, WS2 9AD, United Kingdom.<br>
            All rights reserved.
        </div>
    </div>
</body>
</html>
