<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Alert #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 24px;
            color: #333333;
        }
        .container {
            max-width: 650px;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #10b981;
            letter-spacing: 1px;
        }
        .body {
            padding: 30px;
            line-height: 1.5;
        }
        .alert-box {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .risk-low {
            background-color: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }
        .risk-medium {
            background-color: #fefce8;
            border: 1px solid #fde047;
            color: #854d0e;
        }
        .risk-high {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        .card {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .card-title {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        th {
            background: #f3f4f6;
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
            <div>
                <h1>KENKIE &bull; STORE ALERT</h1>
                <div style="font-size: 13px; color: #9ca3af; margin-top: 4px;">New Order Notification</div>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 18px; font-weight: 700; color: #ffffff;">£{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <div class="body">
            @php
                $riskScore = $order->risk_score ?: 'low';
                $riskClass = match($riskScore) {
                    'high' => 'risk-high',
                    'medium' => 'risk-medium',
                    default => 'risk-low',
                };
            @endphp
            <div class="alert-box {{ $riskClass }}">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong>Fraud & Risk Assessment:</strong>
                    <span style="text-transform: uppercase; font-weight: 800; font-size: 12px; letter-spacing: 1px;">
                        {{ strtoupper($riskScore) }} RISK
                    </span>
                </div>
                @if (!empty($order->risk_flags) && is_array($order->risk_flags))
                    <ul style="margin: 8px 0 0 0; padding-left: 20px; font-size: 13px;">
                        @foreach ($order->risk_flags as $flag)
                            <li>{{ $flag }}</li>
                        @endforeach
                    </ul>
                @else
                    <div style="font-size: 13px; margin-top: 4px;">Standard order indicators. No high-risk anomalies detected.</div>
                @endif
            </div>

            <div class="info-grid">
                <div class="card">
                    <div class="card-title">Order Info</div>
                    <div><strong>Order #:</strong> {{ $order->order_number }}</div>
                    <div><strong>Date:</strong> {{ $order->created_at?->format('d M Y, H:i') }}</div>
                    <div><strong>Payment:</strong> {{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})</div>
                    <div><strong>Status:</strong> <span style="color: #0da487; font-weight: 600;">{{ ucfirst($order->status) }}</span></div>
                </div>

                <div class="card">
                    <div class="card-title">Customer & Delivery</div>
                    <div><strong>Name:</strong> {{ $order->customer_name }}</div>
                    <div><strong>Email:</strong> {{ $order->email }}</div>
                    <div><strong>Phone:</strong> {{ $order->phone }}</div>
                    <div><strong>IP:</strong> {{ $order->ip_address ?: 'Not recorded' }}</div>
                    <div style="margin-top: 6px; font-size: 13px; color: #4b5563;">
                        {{ $order->address_line }}, {{ $order->city }} {{ $order->postal_code }}, {{ $order->country }}
                    </div>
                </div>
            </div>

            <h3 style="margin-bottom: 8px; font-size: 16px;">Purchased Items ({{ $order->items->count() }})</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>SKU</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td style="font-family: monospace; font-size: 12px;">{{ $item->sku ?: '-' }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">£{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td class="text-right" style="font-weight: 600;">£{{ number_format((float) $item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">Subtotal:</td>
                        <td class="text-right">£{{ number_format((float) $order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Shipping:</td>
                        <td class="text-right">{{ (float) $order->shipping_fee > 0 ? '£'.number_format((float) $order->shipping_fee, 2) : 'FREE' }}</td>
                    </tr>
                    <tr style="font-size: 16px; font-weight: 700;">
                        <td colspan="4" class="text-right" style="border-top: 2px solid #e5e7eb;">Grand Total:</td>
                        <td class="text-right" style="border-top: 2px solid #e5e7eb; color: #0da487;">£{{ number_format((float) $order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn">View & Process Order in Dashboard &rarr;</a>
            </div>
        </div>

        <div class="footer">
            Automated notification from Kenkie Store &bull; <a href="{{ url('/') }}" style="color: #6b7280;">{{ url('/') }}</a>
        </div>
    </div>
</body>
</html>
