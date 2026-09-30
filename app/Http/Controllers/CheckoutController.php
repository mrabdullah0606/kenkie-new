<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\StorefrontCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
            'payment_method' => ['required', 'in:cash_on_delivery'],
        ]);
        $cart = StorefrontCart::sanitizeSessionCart($request);

        if (! is_array($cart) || $cart === []) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($request, $validated, $cart): Order {
            $products = Product::query()
                ->whereKey(array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($cart)) {
                StorefrontCart::sanitizeSessionCart($request);
                throw ValidationException::withMessages(['cart' => 'A product in your cart is no longer available.']);
            }

            $lineItems = [];
            $subtotalCents = 0;

            foreach ($cart as $productId => $quantity) {
                $product = $products->get((int) $productId);
                $quantity = filter_var($quantity, FILTER_VALIDATE_INT);

                if (! $product || ! $product->is_active || ! $quantity || $quantity > $product->stock) {
                    throw ValidationException::withMessages(['cart' => 'Please review product availability and quantities.']);
                }

                $unitPriceCents = (int) round((float) $product->price * 100);
                $lineTotalCents = $unitPriceCents * $quantity;
                $subtotalCents += $lineTotalCents;
                $lineItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unitPriceCents' => $unitPriceCents,
                    'lineTotalCents' => $lineTotalCents,
                ];
            }

            $shippingFeeCents = $subtotalCents >= StorefrontCart::FREE_SHIPPING_THRESHOLD_CENTS
                ? 0
                : StorefrontCart::SHIPPING_FEE_CENTS;

            $order = Order::query()->create([
                ...$validated,
                'uuid' => (string) Str::uuid(),
                'user_id' => $request->user()?->id,
                'status' => 'pending',
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

        $request->session()->forget('cart');

        return redirect()->route('orders.confirmation', $order->uuid);
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
