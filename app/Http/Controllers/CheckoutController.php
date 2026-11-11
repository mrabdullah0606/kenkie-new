<?php

namespace App\Http\Controllers;

use App\Mail\AdminNewOrderNotificationMail;
use App\Mail\CustomerOrderConfirmationMail;
use App\Models\Order;
use App\Models\PaymentSetting;
use App\Models\Product;
use App\Services\FraudDetectionService;
use App\Services\PayPalService;
use App\Services\StorefrontCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Coupon;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function index(Request $request, StorefrontCart $cart): View|RedirectResponse
    {
        $contents = $cart->contents($request);

        if ($contents['cartItems']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $contents['shippingFeeCents'] = $cart->shippingFeeCents($contents['cartSubtotalCents']);
        $contents['couponDiscountCents'] = $contents['couponDiscountCents'] ?? StorefrontCart::getAppliedCouponDiscountCents($contents['cartSubtotalCents']);
        $contents['appliedCoupon'] = $request->session()->get('applied_coupon');
        $contents['orderTotalCents'] = max(0, $contents['cartSubtotalCents'] - $contents['couponDiscountCents'] + $contents['shippingFeeCents']);

        $paymentSettings = PaymentSetting::getSettings();
        $contents['stripeKey'] = PaymentSetting::getStripeKey();
        $contents['stripeEnabled'] = $paymentSettings->stripe_enabled;
        $contents['paypalEnabled'] = $paymentSettings->paypal_enabled;
        $contents['codEnabled'] = $paymentSettings->cod_enabled;
        $contents['codInstructions'] = $paymentSettings->cod_instructions;

        $savedDetails = $request->session()->get('checkout_customer_details', []);
        if (empty($savedDetails) && $request->user()) {
            $lastOrder = $request->user()->orders()->latest()->first();
            if ($lastOrder) {
                $savedDetails = [
                    'customer_name' => $lastOrder->customer_name,
                    'email' => $lastOrder->email,
                    'phone' => $lastOrder->phone,
                    'address_line' => $lastOrder->address_line,
                    'city' => $lastOrder->city,
                    'region' => $lastOrder->region,
                    'postal_code' => $lastOrder->postal_code,
                    'country' => $lastOrder->country,
                ];
            }
        }

        if (! empty($savedDetails['country'])) {
            $countryNorm = strtolower(trim((string) $savedDetails['country']));
            if (! in_array($countryNorm, ['united kingdom', 'uk', 'gb', 'great britain', 'england', 'scotland', 'wales', 'northern ireland'], true)) {
                $savedDetails['country'] = 'United Kingdom';
            }
        } else {
            $savedDetails['country'] = 'United Kingdom';
        }

        $contents['customerDetails'] = $savedDetails;

        return view('website.pages.checkout', $contents);
    }

    public function store(Request $request): RedirectResponse
    {
        $paymentSettings = PaymentSetting::getSettings();
        $allowedPaymentMethods = [];
        if ($paymentSettings->stripe_enabled) {
            $allowedPaymentMethods[] = 'stripe';
        }
        if ($paymentSettings->paypal_enabled) {
            $allowedPaymentMethods[] = 'paypal';
        }
        if ($paymentSettings->cod_enabled) {
            $allowedPaymentMethods[] = 'cash_on_delivery';
        }
        if (empty($allowedPaymentMethods)) {
            $allowedPaymentMethods = ['stripe', 'paypal'];
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'region' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:30'],
            'country' => [
                'required',
                'string',
                'max:120',
                function ($attribute, $value, $fail) {
                    $normalized = strtolower(trim((string) $value));
                    $allowed = ['united kingdom', 'uk', 'gb', 'great britain', 'england', 'scotland', 'wales', 'northern ireland'];
                    if (! in_array($normalized, $allowed, true)) {
                        $fail('Orders can only be accepted and delivered to addresses within the United Kingdom.');
                    }
                },
            ],
            'payment_method' => ['required', 'in:'.implode(',', $allowedPaymentMethods)],
        ], [
            'country.*' => 'Orders can only be accepted and delivered to addresses within the United Kingdom.',
        ]);

        $validated['country'] = 'United Kingdom';

        $request->session()->put('checkout_customer_details', $validated);

        $cart = StorefrontCart::sanitizeSessionCart($request);

        if (! is_array($cart) || $cart === []) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $lineItems = [];
        $subtotalCents = 0;
        $shippingFeeCents = 0;
        $couponDiscountCents = 0;
        $couponCode = null;

        $order = DB::transaction(function () use ($request, $validated, $cart, &$lineItems, &$subtotalCents, &$shippingFeeCents, &$couponDiscountCents, &$couponCode): Order {
            $productIds = [];
            foreach (array_keys($cart) as $cartKey) {
                $pId = str_contains((string) $cartKey, ':')
                    ? (int) explode(':', (string) $cartKey)[0]
                    : (int) $cartKey;
                $productIds[] = $pId;
            }

            $products = Product::query()
                ->whereIn('id', array_unique($productIds))
                ->with(['activeOffers', 'activeVariations'])
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cart as $cartKey => $quantity) {
                $cartKeyStr = (string) $cartKey;
                $variationId = null;

                if (str_contains($cartKeyStr, ':')) {
                    [$pId, $vId] = explode(':', $cartKeyStr, 2);
                    $productId = (int) $pId;
                    $variationId = (int) $vId;
                } else {
                    $productId = (int) $cartKeyStr;
                }

                $product = $products->get($productId);
                $quantity = filter_var($quantity, FILTER_VALIDATE_INT);

                if (! $product || ! $product->is_active || ! $quantity) {
                    throw ValidationException::withMessages(['cart' => 'Please review product availability and quantities.']);
                }

                $variation = null;
                $maxStock = (int) $product->stock;

                if ($variationId && $product->relationLoaded('activeVariations')) {
                    $variation = $product->activeVariations->firstWhere('id', $variationId);
                    if (! $variation || ! $variation->is_active) {
                        throw ValidationException::withMessages(['cart' => 'Selected variation is no longer available.']);
                    }
                    $maxStock = (int) $variation->stock;
                }

                if ($quantity > $maxStock) {
                    throw ValidationException::withMessages(['cart' => "Only {$maxStock} units available for {$product->name}."]);
                }

                $unitPrice = $variation
                    ? (float) ($variation->sale_price ?? $variation->regular_price ?? $product->price)
                    : (float) $product->price;

                $unitPriceCents = (int) round($unitPrice * 100);
                $effectiveUnitPriceCents = $unitPriceCents;
                $isProductDiscounted = $product->regular_price && (float) $product->regular_price > (float) $product->price;

                $appliedOffer = $product->relationLoaded('activeOffers')
                    ? $product->activeOffers
                        ->filter(function ($offer) use ($quantity, $isProductDiscounted) {
                            if ($isProductDiscounted && ! $offer->allow_on_discounted) {
                                return false;
                            }

                            return $quantity >= $offer->min_quantity;
                        })
                        ->sortByDesc('discount_percentage')
                        ->first()
                    : null;

                if ($appliedOffer) {
                    $discountFactor = (100 - (float) $appliedOffer->discount_percentage) / 100;
                    $effectiveUnitPriceCents = (int) round($unitPriceCents * $discountFactor);
                }

                $lineTotalCents = $effectiveUnitPriceCents * $quantity;
                $subtotalCents += $lineTotalCents;

                $variationLabel = '';
                if ($variation) {
                    $parts = array_filter([
                        $variation->name,
                        $variation->color ? 'Color: '.$variation->color : null,
                        $variation->size ? 'Size: '.$variation->size : null,
                        $variation->material ? 'Material: '.$variation->material : null,
                    ]);
                    $variationLabel = implode(' · ', $parts);
                }

                $displayName = $variation && $variationLabel
                    ? $product->name.' ('.$variationLabel.')'
                    : $product->name;

                $lineItems[] = [
                    'product' => $product,
                    'variation' => $variation,
                    'displayName' => $displayName,
                    'sku' => $variation?->sku ?: $product->sku,
                    'quantity' => $quantity,
                    'unitPriceCents' => $effectiveUnitPriceCents,
                    'lineTotalCents' => $lineTotalCents,
                ];
            }

            $shippingFeeCents = StorefrontCart::calculateShippingFeeCents($subtotalCents, $lineItems);
            $couponDiscountCents = StorefrontCart::getAppliedCouponDiscountCents($subtotalCents);
            $appliedCoupon = $request->session()->get('applied_coupon');
            $couponCode = ($couponDiscountCents > 0 && is_array($appliedCoupon)) ? ($appliedCoupon['code'] ?? null) : null;
            $orderTotalCents = max(0, $subtotalCents - $couponDiscountCents + $shippingFeeCents);

            $initialPaymentStatus = 'pending';
            $initialStatus = 'pending';
            if ($validated['payment_method'] === 'stripe') {
                $stripeSecret = PaymentSetting::getStripeSecret();
                if (! $stripeSecret || str_contains($stripeSecret, 'placeholder') || ! str_starts_with($stripeSecret, 'sk_')) {
                    $initialPaymentStatus = 'paid';
                    $initialStatus = 'processing';
                }
            } elseif ($validated['payment_method'] === 'paypal') {
                $paypalSecret = PaymentSetting::getPayPalSecret();
                if (! $paypalSecret || str_contains($paypalSecret, 'placeholder')) {
                    $initialPaymentStatus = 'paid';
                    $initialStatus = 'processing';
                }
            }

            $totalAmount = (float) $orderTotalCents / 100;
            $fraudEval = FraudDetectionService::evaluate($validated, $lineItems, $request, $totalAmount);

            $order = Order::query()->create([
                ...$validated,
                'uuid' => (string) Str::uuid(),
                'user_id' => $request->user()?->id,
                'status' => $initialStatus,
                'payment_status' => $initialPaymentStatus,
                'ip_address' => $request->ip(),
                'risk_score' => $fraudEval['score'],
                'risk_flags' => $fraudEval['flags'],
                'subtotal' => $this->money($subtotalCents),
                'discount_amount' => $this->money($couponDiscountCents),
                'coupon_code' => $couponCode,
                'shipping_fee' => $this->money($shippingFeeCents),
                'total' => $this->money($orderTotalCents),
            ]);

            foreach ($lineItems as $lineItem) {
                $product = $lineItem['product'];
                $variation = $lineItem['variation'] ?? null;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $lineItem['displayName'],
                    'sku' => $lineItem['sku'],
                    'unit_price' => $this->money($lineItem['unitPriceCents']),
                    'quantity' => $lineItem['quantity'],
                    'line_total' => $this->money($lineItem['lineTotalCents']),
                ]);

                if ($variation) {
                    $variation->decrement('stock', $lineItem['quantity']);
                }
                $product->decrement('stock', $lineItem['quantity']);
            }

            return $order;
        });

        $couponDiscountCents = (int) round(((float) ($order->discount_amount ?? 0)) * 100);
        $couponCode = $order->coupon_code;
        $shippingFeeCents = (int) round(((float) ($order->shipping_fee ?? 0)) * 100);
        $subtotalCents = (int) round(((float) ($order->subtotal ?? 0)) * 100);

        // Handle Stripe payment
        if ($validated['payment_method'] === 'stripe') {
            $stripeSecret = PaymentSetting::getStripeSecret();

            if ($stripeSecret && ! str_contains($stripeSecret, 'placeholder') && str_starts_with($stripeSecret, 'sk_')) {
                try {
                    Stripe::setApiKey($stripeSecret);

                    $stripeLineItems = [];
                    foreach ($lineItems as $item) {
                        $stripeLineItems[] = [
                            'price_data' => [
                                'currency' => 'gbp',
                                'product_data' => [
                                    'name' => $item['product']->name,
                                    'description' => $item['product']->sku ? 'SKU: '.$item['product']->sku : null,
                                ],
                                'unit_amount' => $item['unitPriceCents'],
                            ],
                            'quantity' => $item['quantity'],
                        ];
                    }

                    if ($shippingFeeCents > 0) {
                        $stripeLineItems[] = [
                            'price_data' => [
                                'currency' => 'gbp',
                                'product_data' => [
                                    'name' => 'Shipping Fee',
                                ],
                                'unit_amount' => $shippingFeeCents,
                            ],
                            'quantity' => 1,
                        ];
                    }

                    $sessionParams = [
                        'payment_method_types' => ['card'],
                        'customer_email' => $order->email,
                        'line_items' => $stripeLineItems,
                        'mode' => 'payment',
                        'success_url' => route('checkout.stripe.success', ['order' => $order->uuid]).'?session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => route('checkout.stripe.cancel', ['order' => $order->uuid]),
                        'metadata' => [
                            'order_id' => $order->id,
                            'order_uuid' => $order->uuid,
                        ],
                    ];

                    if ($couponDiscountCents > 0) {
                        try {
                            $stripeCoupon = Coupon::create([
                                'amount_off' => $couponDiscountCents,
                                'currency' => 'gbp',
                                'duration' => 'once',
                                'name' => ($couponCode ?: 'COUPON').' Discount',
                            ]);
                            $sessionParams['discounts'] = [['coupon' => $stripeCoupon->id]];
                        } catch (\Throwable) {
                            // Proceed without Stripe coupon parameter
                        }
                    }

                    $session = StripeSession::create($sessionParams);

                    $order->update(['stripe_session_id' => $session->id]);

                    return redirect()->away($session->url);
                } catch (\Throwable $e) {
                    Log::error('Stripe checkout session failed: '.$e->getMessage());

                    return redirect()->route('cart.index')->with('error', 'Stripe checkout could not be initiated: '.$e->getMessage());
                }
            } else {
                // If test placeholder keys are present in dev/local, simulate successful payment
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);
                $request->session()->forget(['cart', 'checkout_customer_details', 'applied_coupon']);
                self::sendOrderEmails($order);

                return redirect()->route('orders.confirmation', $order->uuid)->with('success', 'Order placed with Stripe (Test Simulation Mode).');
            }
        }

        // Handle PayPal payment
        if ($validated['payment_method'] === 'paypal') {
            $paypalClientId = PaymentSetting::getPayPalClientId();
            $paypalSecret = PaymentSetting::getPayPalSecret();

            if ($paypalClientId && $paypalSecret && ! str_contains($paypalSecret, 'placeholder') && ! str_contains($paypalClientId, 'placeholder')) {
                try {
                    $paypalOrder = PayPalService::createOrder($order, $lineItems, $subtotalCents, $shippingFeeCents, $couponDiscountCents);
                    $order->update(['paypal_order_id' => $paypalOrder['id']]);

                    return redirect()->away($paypalOrder['approve_url']);
                } catch (\Throwable $e) {
                    Log::error('PayPal checkout session failed: '.$e->getMessage());

                    return redirect()->route('cart.index')->with('error', 'PayPal checkout could not be initiated: '.$e->getMessage());
                }
            } else {
                // Test simulation mode for PayPal
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);
                $request->session()->forget(['cart', 'checkout_customer_details', 'applied_coupon']);
                self::sendOrderEmails($order);

                return redirect()->route('orders.confirmation', $order->uuid)->with('success', 'Order placed with PayPal (Test Simulation Mode).');
            }
        }

        // Cash on delivery (if enabled)
        $request->session()->forget(['cart', 'checkout_customer_details', 'applied_coupon']);
        self::sendOrderEmails($order);

        return redirect()->route('orders.confirmation', $order->uuid);
    }

    public function stripeSuccess(Request $request, Order $order): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        $stripeSecret = PaymentSetting::getStripeSecret();

        if ($sessionId && $stripeSecret && ! str_contains($stripeSecret, 'placeholder') && str_starts_with($stripeSecret, 'sk_')) {
            try {
                Stripe::setApiKey($stripeSecret);
                $session = StripeSession::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'stripe_payment_intent_id' => is_string($session->payment_intent) ? $session->payment_intent : null,
                    ]);
                    self::sendOrderEmails($order);
                }
            } catch (\Throwable $e) {
                Log::error('Stripe verification failed: '.$e->getMessage());
            }
        }

        // Only clear the cart and details once payment has succeeded
        $request->session()->forget(['cart', 'checkout_customer_details']);

        return redirect()->route('orders.confirmation', $order->uuid);
    }

    public function stripeCancel(Request $request, Order $order): RedirectResponse
    {
        if ($order->payment_status === 'pending') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::query()->where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled',
            ]);
        }

        // Restore customer details into session so inputs stay pre-filled
        $request->session()->put('checkout_customer_details', [
            'customer_name' => $order->customer_name,
            'email' => $order->email,
            'phone' => $order->phone,
            'address_line' => $order->address_line,
            'city' => $order->city,
            'region' => $order->region,
            'postal_code' => $order->postal_code,
            'country' => $order->country,
            'payment_method' => $order->payment_method,
        ]);

        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled. Your details and cart items have been preserved so you can review or choose another payment method.');
    }

    public function paypalSuccess(Request $request, Order $order): RedirectResponse
    {
        $token = $request->query('token');
        $paypalOrderId = $token ?: $order->paypal_order_id;
        $paypalSecret = PaymentSetting::getPayPalSecret();

        if ($paypalOrderId && $paypalSecret && ! str_contains($paypalSecret, 'placeholder')) {
            try {
                $capture = PayPalService::captureOrder($paypalOrderId);
                $status = $capture['status'] ?? '';

                if ($status === 'COMPLETED' || $status === 'APPROVED') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'paypal_order_id' => $paypalOrderId,
                    ]);
                    self::sendOrderEmails($order);
                }
            } catch (\Throwable $e) {
                Log::error('PayPal capture error: '.$e->getMessage());
            }
        }

        $request->session()->forget(['cart', 'checkout_customer_details']);

        return redirect()->route('orders.confirmation', $order->uuid);
    }

    public function paypalCancel(Request $request, Order $order): RedirectResponse
    {
        if ($order->payment_status === 'pending') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::query()->where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled',
            ]);
        }

        $request->session()->put('checkout_customer_details', [
            'customer_name' => $order->customer_name,
            'email' => $order->email,
            'phone' => $order->phone,
            'address_line' => $order->address_line,
            'city' => $order->city,
            'region' => $order->region,
            'postal_code' => $order->postal_code,
            'country' => $order->country,
            'payment_method' => $order->payment_method,
        ]);

        return redirect()->route('checkout.index')
            ->with('error', 'PayPal payment was cancelled. Your details and cart items have been preserved so you can review or choose another payment method.');
    }

    public function confirmation(Request $request, Order $order): View
    {
        if ($request->user() && $order->user_id !== $request->user()->id) {
            abort(404);
        }

        return view('website.pages.confirmation', [
            'order' => $order->load('items'),
        ]);
    }

    /**
     * Dispatch email notifications to Admin and Customer.
     */
    public static function sendOrderEmails(Order $order): void
    {
        $order->loadMissing('items');

        // 1. Notify Admin of new order
        try {
            $adminEmail = config('mail.admin_address') ?: config('mail.from.address', 'info@kenkie.com');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminNewOrderNotificationMail($order));
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send admin order notification for #{$order->order_number}: ".$e->getMessage());
        }

        // 2. Notify Customer with order confirmation
        try {
            if (! empty($order->email)) {
                Mail::to($order->email)->send(new CustomerOrderConfirmationMail($order));
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send customer order confirmation for #{$order->order_number}: ".$e->getMessage());
        }
    }

    private function money(int $cents): string
    {
        return number_format($cents / 100, 2, '.', '');
    }
}
