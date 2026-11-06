<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Alert #{{ $order->order_number }}</title>
    <style>
        /* Base styles */
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
        /* Mobile Breakpoints */
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
                padding: 20px 18px !important;
            }
            .header-total {
                font-size: 20px !important;
            }
            .body-cell {
                padding: 20px 16px !important;
            }
            .stack-column {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-bottom: 14px !important;
            }
            .item-table td {
                padding: 10px 6px !important;
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
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-wrapper" style="max-width: 640px; margin: 0 auto;">
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
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td valign="middle" align="left">
                                            <div style="display: inline-block; background-color: rgba(13, 164, 135, 0.2); border: 1px solid rgba(13, 164, 135, 0.4); border-radius: 4px; padding: 3px 8px; font-size: 11px; font-weight: 700; color: #34d399; letter-spacing: 1px; text-transform: uppercase;">
                                                Kenkie &bull; Store Alert
                                            </div>
                                            <h1 style="margin: 8px 0 0 0; font-size: 20px; font-weight: 800; color: #ffffff; letter-spacing: -0.3px;">
                                                New Order Notification
                                            </h1>
                                        </td>
                                        <td valign="middle" align="right" style="padding-left: 12px;">
                                            <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Grand Total</div>
                                            <div class="header-total" style="font-size: 24px; font-weight: 800; color: #34d399; line-height: 1.2;">
                                                £{{ number_format((float) $order->total, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- Email Body -->
                        <tr>
                            <td class="body-cell" style="padding: 28px 30px;">
                                
                                @php
                                    $riskScore = $order->risk_score ?: 'low';
                                    $isHigh = $riskScore === 'high';
                                    $isMed = $riskScore === 'medium';
                                    $riskBg = $isHigh ? '#fef2f2' : ($isMed ? '#fefce8' : '#f0fdf4');
                                    $riskBorder = $isHigh ? '#fca5a5' : ($isMed ? '#fde047' : '#86efac');
                                    $riskText = $isHigh ? '#991b1b' : ($isMed ? '#854d0e' : '#166534');
                                    $riskBadgeBg = $isHigh ? '#dc2626' : ($isMed ? '#ca8a04' : '#16a34a');
                                @endphp

                                <!-- Risk Assessment Banner -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: {{ $riskBg }}; border: 1px solid {{ $riskBorder }}; border-radius: 8px; margin-bottom: 22px;">
                                    <tr>
                                        <td style="padding: 14px 18px;">
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td valign="middle" align="left">
                                                        <strong style="color: {{ $riskText }}; font-size: 14px;">Fraud & Risk Assessment:</strong>
                                                    </td>
                                                    <td valign="middle" align="right">
                                                        <span style="display: inline-block; background-color: {{ $riskBadgeBg }}; color: #ffffff; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 800; letter-spacing: 0.8px; text-transform: uppercase;">
                                                            {{ strtoupper($riskScore) }} RISK
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                            @if (!empty($order->risk_flags) && is_array($order->risk_flags))
                                                <ul style="margin: 8px 0 0 0; padding-left: 20px; font-size: 13px; color: {{ $riskText }}; line-height: 1.5;">
                                                    @foreach ($order->risk_flags as $flag)
                                                        <li>{{ $flag }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div style="font-size: 12.5px; color: {{ $riskText }}; margin-top: 5px;">
                                                    Standard order indicators. No high-risk anomalies detected.
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>

                                <!-- Order Details Card (Full width on mobile, stacked cleanly) -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 14px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                                    <tr>
                                        <td style="padding: 16px 20px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                                                📋 Order Information
                                            </div>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13.5px; line-height: 1.6; color: #334155;">
                                                <tr>
                                                    <td width="38%" style="color: #64748b; font-weight: 500; padding: 2px 0;">Order Number:</td>
                                                    <td style="font-weight: 700; color: #0f172a; padding: 2px 0;">{{ $order->order_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">Date & Time:</td>
                                                    <td style="color: #0f172a; padding: 2px 0;">{{ $order->created_at?->format('d M Y, H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">Payment Method:</td>
                                                    <td style="color: #0f172a; font-weight: 600; padding: 2px 0;">
                                                        {{ strtoupper($order->payment_method) }}
                                                        <span style="font-weight: normal; color: #64748b;">({{ ucfirst($order->payment_status) }})</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">Fulfillment Status:</td>
                                                    <td style="color: #0da487; font-weight: 700; padding: 2px 0;">{{ ucfirst($order->status) }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Customer & Delivery Details Card (Stacked with word-break) -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                                    <tr>
                                        <td style="padding: 16px 20px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                                                👤 Customer &amp; Delivery Details
                                            </div>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13.5px; line-height: 1.6; color: #334155;">
                                                <tr>
                                                    <td width="38%" style="color: #64748b; font-weight: 500; padding: 2px 0;">Customer Name:</td>
                                                    <td style="font-weight: 700; color: #0f172a; padding: 2px 0; word-break: break-word;">{{ $order->customer_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">Email:</td>
                                                    <td style="padding: 2px 0; word-break: break-all;">
                                                        <a href="mailto:{{ $order->email }}" style="color: #0da487; text-decoration: none; font-weight: 600;">{{ $order->email }}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">Phone Number:</td>
                                                    <td style="color: #0f172a; padding: 2px 0;">{{ $order->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color: #64748b; font-weight: 500; padding: 2px 0;">IP Address:</td>
                                                    <td style="color: #64748b; font-family: monospace; font-size: 12px; padding: 2px 0;">{{ $order->ip_address ?: 'Not recorded' }}</td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" style="color: #64748b; font-weight: 500; padding: 4px 0 2px 0;">Shipping Address:</td>
                                                    <td style="color: #0f172a; padding: 4px 0 2px 0; word-break: break-word;">
                                                        <strong>{{ $order->address_line }}</strong><br>
                                                        {{ $order->city }}@if($order->region), {{ $order->region }}@endif<br>
                                                        {{ $order->postal_code }}, {{ $order->country }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Purchased Items Header -->
                                <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
                                    Purchased Items ({{ $order->items->count() }})
                                </div>

                                <!-- Purchased Items Table -->
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
                                                    @if($item->sku)
                                                        <div style="font-size: 11px; color: #64748b; font-family: monospace; margin-top: 3px;">
                                                            SKU: {{ $item->sku }}
                                                        </div>
                                                    @endif
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
                                            <td colspan="2" align="right" style="padding: 12px 14px; font-size: 15px; color: #0f172a; font-weight: 800;">Grand Total:</td>
                                            <td align="right" style="padding: 12px 14px; font-size: 18px; color: #0da487; font-weight: 800;">
                                                £{{ number_format((float) $order->total, 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- CTA Action Button -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td align="center" style="padding-top: 10px; padding-bottom: 6px;">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-action" style="display: inline-block; background-color: #0da487; color: #ffffff !important; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 28px; border-radius: 8px; box-shadow: 0 2px 6px rgba(13, 164, 135, 0.35);">
                                                Open &amp; Process Order in Admin Dashboard &rarr;
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
