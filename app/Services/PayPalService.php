<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentSetting;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class PayPalService
{
    /**
     * Get the base URL according to configured environment mode.
     */
    public static function getBaseUrl(): string
    {
        $settings = PaymentSetting::getSettings();
        $mode = $settings->paypal_mode ?? 'sandbox';

        return $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Get an OAuth2 access token from PayPal.
     */
    public static function getAccessToken(): string
    {
        $clientId = PaymentSetting::getPayPalClientId();
        $secret = PaymentSetting::getPayPalSecret();

        if (empty($clientId) || empty($secret)) {
            throw new Exception('PayPal credentials are missing. Please enter your PayPal Client ID and Secret in Admin &gt; Payment Gateways.');
        }

        $response = Http::asForm()
            ->connectTimeout(5)
            ->timeout(15)
            ->withBasicAuth($clientId, $secret)
            ->post(self::getBaseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            $errorDesc = $response->json('error_description') ?? $response->json('error') ?? $response->body();
            Log::error('PayPal OAuth failed: '.$errorDesc);
            throw new Exception('PayPal Authentication Failed: '.$errorDesc);
        }

        $accessToken = $response->json('access_token');
        if (empty($accessToken)) {
            throw new Exception('PayPal did not return a valid access token.');
        }

        return $accessToken;
    }

    /**
     * Create a new PayPal Checkout Order.
     *
     * @param  array<int, mixed>  $lineItems
     * @return array{id: string, approve_url: string}
     */
    public static function createOrder(Order $order, array $lineItems, int $subtotalCents, int $shippingFeeCents): array
    {
        $accessToken = self::getAccessToken();

        $items = [];
        foreach ($lineItems as $item) {
            $items[] = [
                'name' => mb_substr((string) ($item['displayName'] ?? $item['product']->name), 0, 127),
                'quantity' => (string) $item['quantity'],
                'unit_amount' => [
                    'currency_code' => 'GBP',
                    'value' => number_format($item['unitPriceCents'] / 100, 2, '.', ''),
                ],
            ];
        }

        $subtotal = number_format($subtotalCents / 100, 2, '.', '');
        $shipping = number_format($shippingFeeCents / 100, 2, '.', '');
        $total = number_format(($subtotalCents + $shippingFeeCents) / 100, 2, '.', '');

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $order->uuid,
                    'description' => 'Kenkie Order #'.($order->order_number ?? $order->id),
                    'custom_id' => (string) $order->id,
                    'amount' => [
                        'currency_code' => 'GBP',
                        'value' => $total,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'GBP',
                                'value' => $subtotal,
                            ],
                            'shipping' => [
                                'currency_code' => 'GBP',
                                'value' => $shipping,
                            ],
                        ],
                    ],
                    'items' => $items,
                ],
            ],
            'application_context' => [
                'brand_name' => 'KENKIE',
                'locale' => 'en-GB',
                'landing_page' => 'BILLING',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
                'return_url' => route('checkout.paypal.success', ['order' => $order->uuid]),
                'cancel_url' => route('checkout.paypal.cancel', ['order' => $order->uuid]),
            ],
        ];

        $response = Http::withToken($accessToken)
            ->connectTimeout(5)
            ->timeout(20)
            ->post(self::getBaseUrl().'/v2/checkout/orders', $payload);

        if (! $response->successful()) {
            $msg = $response->json('message') ?? $response->body();
            Log::error('PayPal Create Order error: '.$msg);
            throw new Exception('PayPal Order creation failed: '.$msg);
        }

        $orderData = $response->json();
        $orderId = $orderData['id'] ?? null;

        $approveUrl = null;
        if (! empty($orderData['links']) && is_array($orderData['links'])) {
            foreach ($orderData['links'] as $link) {
                if (($link['rel'] ?? '') === 'approve') {
                    $approveUrl = $link['href'];
                    break;
                }
            }
        }

        if (! $orderId || ! $approveUrl) {
            throw new Exception('PayPal did not return an approval checkout URL.');
        }

        return [
            'id' => $orderId,
            'approve_url' => $approveUrl,
        ];
    }

    /**
     * Capture payment for an approved PayPal order.
     *
     * @return array<string, mixed>
     */
    public static function captureOrder(string $paypalOrderId): array
    {
        $accessToken = self::getAccessToken();

        $response = Http::withToken($accessToken)
            ->connectTimeout(5)
            ->timeout(20)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post(self::getBaseUrl()."/v2/checkout/orders/{$paypalOrderId}/capture", new \stdClass);

        if (! $response->successful()) {
            $msg = $response->json('message') ?? $response->body();
            Log::error("PayPal Capture failed for order {$paypalOrderId}: ".$msg);
            throw new Exception('PayPal capture error: '.$msg);
        }

        return $response->json();
    }

    /**
     * Test credentials against PayPal API.
     *
     * @return array{success: bool, mode: string, message: string}
     */
    public static function testConnection(): array
    {
        $clientId = PaymentSetting::getPayPalClientId();
        $secret = PaymentSetting::getPayPalSecret();
        $mode = PaymentSetting::getSettings()->paypal_mode ?? 'sandbox';

        if (empty($clientId) || empty($secret)) {
            return [
                'success' => false,
                'mode' => strtoupper($mode),
                'message' => 'PayPal Client ID and Secret Key must be filled and saved first.',
            ];
        }

        if (str_contains($secret, 'placeholder') || str_contains($clientId, 'placeholder')) {
            return [
                'success' => true,
                'mode' => 'SIMULATION',
                'message' => 'Local placeholder keys detected. In dev environment, PayPal simulation mode is active.',
            ];
        }

        try {
            $token = self::getAccessToken();

            return [
                'success' => true,
                'mode' => strtoupper($mode),
                'message' => 'PayPal API authentication successful! Connected in ['.strtoupper($mode).'] mode.',
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'mode' => strtoupper($mode),
                'message' => $e->getMessage(),
            ];
        }
    }
}
