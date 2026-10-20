<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 24px;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #0da487;
            padding: 30px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .body {
            padding: 32px 28px;
            line-height: 1.6;
        }
        .order-badge {
            display: inline-block;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        th {
            background: #f9fafb;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            color: #1f2937;
        }
        .text-right {
            text-align: right;
        }
        .btn {
            display: inline-block;
            background-color: #0da487;
            color: #ffffff !important;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }
        .address-box {
            background: #f9fafb;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            margin-top: 20px;
            font-size: 13px;
            line-height: 1.5;
        }
        .footer {
            background: #f9fafb;
            padding: 20px 32px;
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
            <h1>KENKIE</h1>
            <p>Thank you for shopping with us!</p>
        </div>

        <div class="body">
            <div class="order-badge">Order #{{ $order->order_number }} Confirmed</div>

            <p>Hello <strong>{{ $order->customer_name }}</strong>,</p>
            <p>
                We have received your order and are currently preparing it for dispatch. Below are your order details and delivery information:
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if ($item->sku)
                                    <div style="font-size: 11px; color: #6b7280;">SKU: {{ $item->sku }}</div>
                                @endif
                            </td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">£{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td class="text-right">£{{ number_format((float) $item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">Subtotal:</td>
                        <td class="text-right">£{{ number_format((float) $order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Delivery:</td>
                        <td class="text-right">{{ (float) $order->shipping_fee > 0 ? '£'.number_format((float) $order->shipping_fee, 2) : 'FREE' }}</td>
                    </tr>
                    <tr style="font-size: 16px; font-weight: 700;">
                        <td colspan="3" class="text-right" style="border-top: 2px solid #e5e7eb;">Total Paid:</td>
                        <td class="text-right" style="border-top: 2px solid #e5e7eb; color: #0da487;">£{{ number_format((float) $order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="address-box">
                <strong style="color: #111827;">Delivery Address:</strong><br>
                {{ $order->customer_name }}<br>
                {{ $order->address_line }}<br>
                {{ $order->city }}{{ $order->region ? ', '.$order->region : '' }} {{ $order->postal_code }}<br>
                {{ $order->country }}<br>
                Phone: {{ $order->phone }}
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('orders.tracking', $order->uuid) }}" class="btn">Track Your Order Live &rarr;</a>
            </div>

            <p style="font-size: 13px; color: #6b7280; margin-top: 24px; text-align: center;">
                Need help with your order? Contact our support team at <a href="mailto:info@kenkie.com" style="color: #0da487;">info@kenkie.com</a>.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Kenkie Ltd. Walsall, WS2 9AD, United Kingdom.<br>
            All rights reserved.
        </div>
    </div>
</body>
</html>
