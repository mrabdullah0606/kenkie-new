<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration Alert</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 24px;
            color: #333333;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #111827;
            padding: 24px 30px;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #10b981;
            letter-spacing: 0.5px;
        }
        .body {
            padding: 30px;
            line-height: 1.6;
        }
        .user-card {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .user-card-item {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .user-card-item:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: 700;
            color: #374151;
            width: 130px;
            display: inline-block;
        }
        .btn {
            display: inline-block;
            background-color: #0da487;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }
        .footer {
            background: #f9fafb;
            padding: 16px 30px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>KENKIE &bull; ADMIN ALERT</h1>
            <div style="font-size: 13px; color: #9ca3af; margin-top: 4px;">New Customer Registration</div>
        </div>

        <div class="body">
            <p>A new customer has just registered an account on your store:</p>

            <div class="user-card">
                <div class="user-card-item">
                    <span class="label">Customer Name:</span> <strong>{{ $user->name }}</strong>
                </div>
                <div class="user-card-item">
                    <span class="label">Email Address:</span> <a href="mailto:{{ $user->email }}" style="color: #0da487;">{{ $user->email }}</a>
                </div>
                <div class="user-card-item">
                    <span class="label">Account Role:</span> <span style="text-transform: capitalize;">{{ $user->role ?: 'Customer' }}</span>
                </div>
                <div class="user-card-item">
                    <span class="label">Registered At:</span> {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                </div>
                <div class="user-card-item">
                    <span class="label">IP Address:</span> {{ $ipAddress ?: request()->ip() ?: 'Not recorded' }}
                </div>
            </div>

            <div style="text-align: center; margin-top: 26px;">
                <a href="{{ route('admin.customers.index') }}" class="btn">View Customers in Admin Panel &rarr;</a>
            </div>
        </div>

        <div class="footer">
            Automated notification from Kenkie Store &bull; <a href="{{ url('/') }}" style="color: #6b7280;">{{ url('/') }}</a>
        </div>
    </div>
</body>
</html>
