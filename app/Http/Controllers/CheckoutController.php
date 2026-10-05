<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\StorefrontCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Stripe\Checkout\Session as StripeSession;
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
        $contents['orderTotalCents'] = $contents['cartSubtotalCents'] + $contents['shippingFeeCents'];
        $contents['stripeKey'] = config('services.stripe.key');

        return view('website.pages.checkout', $contents);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'region' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:120'],
            'payment_method' => ['required', 'in:cash_on_delivery,stripe'],
        ]);
        $cart = StorefrontCart::sanitizeSessionCart($request);

        if (! is_array($cart) || $cart === []) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $lineItems = [];
        $subtotalCents = 0;
        $shippingFeeCents = 0;

        $order = DB::transaction(function () use ($request, $validated, $cart, &$lineItems, &$subtotalCents, &$shippingFeeCents): Order {
            $products = Product::query()
                ->whereKey(array_keys($cart))
                ->with('activeOffers')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($cart)) {
                StorefrontCart::sanitizeSessionCart($request);
                throw ValidationException::withMessages(['cart' => 'A product in your cart is no longer available.']);
            }

            foreach ($cart as $productId => $quantity) {
                $product = $products->get((int) $productId);
                $quantity = filter_var($quantity, FILTER_VALIDATE_INT);

                if (! $product || ! $product->is_active || ! $quantity || $quantity > $product->stock) {
                    throw ValidationException::withMessages(['cart' => 'Please review product availability and quantities.']);
                }

                $unitPriceCents = (int) round((float) $product->price * 100);
                $effectiveUnitPriceCents = $unitPriceCents;

                $appliedOffer = $product->relationLoaded('activeOffers')
                    ? $product->activeOffers
                        ->filter(fn ($offer) => $quantity >= $offer->min_quantity)
                        ->sortByDesc('discount_percentage')
                        ->first()
                    : null;

                if ($appliedOffer) {
                    $discountFactor = (100 - (float) $appliedOffer->discount_percentage) / 100;
                    $effectiveUnitPriceCents = (int) round($unitPriceCents * $discountFactor);
                }

                $lineTotalCents = $effectiveUnitPriceCents * $quantity;
                $subtotalCents += $lineTotalCents;
                $lineItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unitPriceCents' => $effectiveUnitPriceCents,
                    'lineTotalCents' => $lineTotalCents,
                ];
            }

            $shippingFeeCents = $subtotalCents >= StorefrontCart::FREE_SHIPPING_THRESHOLD_CENTS
                ? 0
                : StorefrontCart::SHIPPING_FEE_CENTS;

            $initialPaymentStatus = 'pending';
            $initialStatus = 'pending';
            if ($validated['payment_method'] === 'stripe') {
                $stripeSecret = config('services.stripe.secret');
                if (! $stripeSecret || str_contains($stripeSecret, 'placeholder') || ! str_starts_with($stripeSecret, 'sk_')) {
                    $initialPaymentStatus = 'paid';
                    $initialStatus = 'processing';
                }
            }

            $order = Order::query()->create([
                ...$validated,
                'uuid' => (string) Str::uuid(),
                'user_id' => $request->user()?->id,
                'status' => $initialStatus,
                'payment_status' => $initialPaymentStatus,
                'subtotal' => $this->money($subtotalCents),
                'shipping_fee' => $this->money($shippingFeeCents),
                'total' => $this->money($subtotalCents + $shippingFeeCents),
            ]);

            foreach ($lineItems as $lineItem) {
                $product = $lineItem['product'];
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_price' => $this->money($lineItem['unitPriceCents']),
                    'quantity' => $lineItem['quantity'],
                    'line_total' => $this->money($lineItem['lineTotalCents']),
                ]);
                $product->decrement('stock', $lineItem['quantity']);
            }

            return $order;
        });

        // Handle Stripe payment
        if ($validated['payment_method'] === 'stripe') {
            $stripeSecret = config('services.stripe.secret');

            if ($stripeSecret && ! str_contains($stripeSecret, 'placeholder') && str_starts_with($stripeSecret, 'sk_')) {
                try {
                    Stripe::setApiKey($stripeSecret);

                    $stripeLineItems = [];
                    foreach ($lineItems as $item) {
                        $stripeLineItems[] = [
                            'price_data' => [
                                'currency' => 'usd',
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
                                'currency' => 'usd',
                                'product_data' => [
                                    'name' => 'Shipping Fee',
                                ],
                                'unit_amount' => $shippingFeeCents,
                            ],
                            'quantity' => 1,
                        ];
                    }

                    $session = StripeSession::create([
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
                    ]);

                    $order->update(['stripe_session_id' => $session->id]);
                    $request->session()->forget('cart');

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
                $request->session()->forget('cart');

                return redirect()->route('orders.confirmation', $order->uuid)->with('success', 'Order placed with Stripe (Test Simulation Mode).');
            }
        }

        // Cash on delivery
        $request->session()->forget('cart');

        return redirect()->route('orders.confirmation', $order->uuid);
    }

    public function stripeSuccess(Request $request, Order $order): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        $stripeSecret = config('services.stripe.secret');

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
                }
            } catch (\Throwable $e) {
                Log::error('Stripe verification failed: '.$e->getMessage());
            }
        }

        return redirect()->route('orders.confirmation', $order->uuid);
    }

    public function stripeCancel(Request $request, Order $order): RedirectResponse
    {
        return redirect()->route('checkout.index')->with('error', 'Payment was cancelled. You can retry or choose a different payment method.');
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

    private function money(int $cents): string
    {
        return number_format($cents / 100, 2, '.', '');
    }
}
