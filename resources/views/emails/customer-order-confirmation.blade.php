<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation #{{ $order->order_number }}</title>
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
        img {
            border: 0;
            outline: none;
            text-decoration: none;
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
                padding: 22px 16px !important;
            }
            .item-table td {
                padding: 10px 8px !important;
                font-size: 13px !important;
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
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-wrapper" style="max-width: 620px; margin: 0 auto;">
            <tr>
                <td>
                    <!-- Main Container -->
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
                        
                        <!-- Header -->
                        <tr>
                            <td class="header-cell" style="background-color: #0da487; padding: 32px 30px; text-align: center;">
                                <div style="font-size: 28px; font-weight: 900; color: #ffffff; letter-spacing: 2px;">
                                    KENKIE
                                </div>
                                <div style="font-size: 14px; color: rgba(255, 255, 255, 0.9); margin-top: 6px; font-weight: 500;">
                                    Order Confirmed &bull; Thank you for shopping with us!
                                </div>
                            </td>
                        </tr>

                        <!-- Body -->
                        <tr>
                            <td class="body-cell" style="padding: 30px;">
                                
                                <p style="font-size: 16px; margin: 0 0 16px 0; color: #0f172a; line-height: 1.5;">
                                    Hello <strong>{{ $order->customer_name }}</strong>,
                                </p>
                                <p style="font-size: 14px; margin: 0 0 22px 0; color: #475569; line-height: 1.6;">
                                    We've received your order and our team is already preparing it for shipment. Below is a complete summary of your order details:
                                </p>

                                <!-- Order Reference Pill -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; margin-bottom: 22px;">
                                    <tr>
                                        <td style="padding: 14px 18px;">
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td valign="middle">
                                                        <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #166534; letter-spacing: 0.5px;">Order Number</div>
                                                        <div style="font-size: 18px; font-weight: 800; color: #166534; font-family: monospace;">{{ $order->order_number }}</div>
                                                    </td>
                                                    <td valign="middle" align="right">
                                                        <span style="display: inline-block; background-color: #16a34a; color: #ffffff; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Delivery Details -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 24px;">
                                    <tr>
                                        <td style="padding: 16px 20px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                                                📦 Delivery Address &amp; Payment
                                            </div>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13.5px; line-height: 1.6; color: #334155;">
                                                <tr>
                                                    <td width="35%" style="color: #64748b; font-weight: 500; padding: 2px 0;">Recipient:</td>
                                                    <td style="font-weight: 700; color: #0f172a; padding: 2px 0; word-break: break-word;">{{ $order->customer_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">Shipping To:</td>
                                                    <td style="color: #0f172a; padding: 2px 0; word-break: break-word;">
                                                        <strong>{{ $order->address_line }}</strong><br>
                                                        {{ $order->city }}@if($order->region), {{ $order->region }}@endif<br>
                                                        {{ $order->postal_code }}, {{ $order->country }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">Contact Phone:</td>
                                                    <td style="color: #0f172a; padding: 2px 0;">{{ $order->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">Payment:</td>
                                                    <td style="color: #0f172a; font-weight: 600; padding: 2px 0;">
                                                        {{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Items Section -->
                                <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
                                    Items in Your Order
                                </div>

                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="item-table" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 24px;">
                                    <thead>
                                        <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                            <th align="left" style="padding: 11px 14px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Product</th>
                                            <th align="center" style="padding: 11px 10px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; width: 50px;">Qty</th>
                                            <th align="right" style="padding: 11px 14px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; width: 85px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td style="padding: 12px 14px; vertical-align: top;">
                                                    <div style="font-weight: 700; color: #0f172a; font-size: 13.5px; line-height: 1.4; word-break: break-word;">
                                                        {{ $item->product_name }}
                                                    </div>
                                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                                        £{{ number_format((float) $item->unit_price, 2) }} each
                                                    </div>
                                                </td>
                                                <td align="center" style="padding: 12px 10px; vertical-align: middle; font-weight: 700; color: #0f172a; font-size: 14px;">
                                                    &times;{{ $item->quantity }}
                                                </td>
                                                <td align="right" style="padding: 12px 14px; vertical-align: middle; font-weight: 700; color: #0f172a; font-size: 14px;">
                                                    £{{ number_format((float) $item->line_total, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #f8fafc;">
                                            <td colspan="2" align="right" style="padding: 9px 14px; font-size: 13px; color: #64748b; font-weight: 500;">Subtotal:</td>
                                            <td align="right" style="padding: 9px 14px; font-size: 13.5px; color: #0f172a; font-weight: 600;">
                                                £{{ number_format((float) $order->subtotal, 2) }}
                                            </td>
                                        </tr>
                                        @if ((float) ($order->discount_amount ?? 0) > 0)
                                            <tr style="background-color: #f0fdf4;">
                                                <td colspan="2" align="right" style="padding: 6px 14px; font-size: 13px; color: #16a34a; font-weight: 600;">
                                                    Coupon Discount {{ $order->coupon_code ? '(' . $order->coupon_code . ')' : '' }}:
                                                </td>
                                                <td align="right" style="padding: 6px 14px; font-size: 13.5px; color: #16a34a; font-weight: 700;">
                                                    -£{{ number_format((float) $order->discount_amount, 2) }}
                                                </td>
                                            </tr>
                                        @endif
                                        <tr style="background-color: #f8fafc;">
                                            <td colspan="2" align="right" style="padding: 6px 14px; font-size: 13px; color: #64748b; font-weight: 500;">Shipping:</td>
                                            <td align="right" style="padding: 6px 14px; font-size: 13.5px; color: #0f172a; font-weight: 600;">
                                                {{ (float) $order->shipping_fee > 0 ? '£'.number_format((float) $order->shipping_fee, 2) : 'FREE' }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #f8fafc; border-top: 2px solid #e2e8f0;">
                                            <td colspan="2" align="right" style="padding: 12px 14px; font-size: 15px; color: #0f172a; font-weight: 800;">Total Paid:</td>
                                            <td align="right" style="padding: 12px 14px; font-size: 18px; color: #0da487; font-weight: 800;">
                                                £{{ number_format((float) $order->total, 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- CTA View Order Status -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td align="center" style="padding-top: 10px; padding-bottom: 12px;">
                                            <a href="{{ route('orders.confirmation', $order->uuid) }}" class="btn-action" style="display: inline-block; background-color: #0da487; color: #ffffff !important; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 28px; border-radius: 8px; box-shadow: 0 2px 6px rgba(13, 164, 135, 0.35);">
                                                View Order Details &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Help & Support Section -->
                                <div style="background-color: #f8fafc; border-radius: 8px; padding: 14px 18px; border: 1px dashed #cbd5e1; margin-top: 16px; text-align: center; font-size: 13px; color: #64748b; line-height: 1.5;">
                                    Need help with your order? Reach our customer care team anytime at<br>
                                    <a href="mailto:info@kenkie.com" style="color: #0da487; font-weight: 700; text-decoration: none;">info@kenkie.com</a>
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
