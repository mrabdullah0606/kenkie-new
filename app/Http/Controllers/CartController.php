<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Services\StorefrontCart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public function index(Request $request, StorefrontCart $cart): View
    {
        return view('website.pages.cart', $cart->contents($request));
    }

    public function store(Request $request, Product $product): Response
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'variation_id' => ['nullable', 'integer'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $variationId = ! empty($validated['variation_id']) ? (int) $validated['variation_id'] : null;

        $variation = null;
        $maxStock = (int) $product->stock;

        if ($variationId) {
            $variation = ProductVariation::query()
                ->where('id', $variationId)
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->first();

            if (! $variation) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected variation is no longer available.',
                    ], 422);
                }

                return back()->withErrors(['variation_id' => 'Selected variation is not available.'])->withInput();
            }

            $maxStock = (int) $variation->stock;
        }

        $cart = StorefrontCart::sanitizeSessionCart($request);
        $cartKey = $variationId ? "{$product->id}:{$variationId}" : (string) $product->id;
        $newQuantity = (int) ($cart[$cartKey] ?? 0) + $quantity;

        if ($newQuantity > $maxStock) {
            $msg = $maxStock > 0 ? "Only {$maxStock} available for this option." : 'This option is currently out of stock.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 422);
            }

            return back()->withErrors(['quantity' => $msg])->withInput();
        }

        $cart[$cartKey] = $newQuantity;
        $request->session()->put('cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart.',
                'quantity' => $newQuantity,
                'cartCount' => array_sum($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Product added to cart.');
    }

    public function update(Request $request, Product $product, StorefrontCart $cartService): Response
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
            'cart_key' => ['nullable', 'string'],
            'variation_id' => ['nullable', 'integer'],
        ]);

        $cart = StorefrontCart::sanitizeSessionCart($request);
        $cartKey = $validated['cart_key']
            ?? (! empty($validated['variation_id']) ? "{$product->id}:{$validated['variation_id']}" : (string) $product->id);

        $quantity = (int) $validated['quantity'];

        if ($quantity <= 0) {
            unset($cart[$cartKey]);
        } else {
            $cart[$cartKey] = $quantity;
        }

        $request->session()->put('cart', $cart);
        $cart = StorefrontCart::sanitizeSessionCart($request);
        $cartData = $cartService->contents($request);
        $shippingFeeCents = $cartService->shippingFeeCents($cartData['cartSubtotalCents']);

        $itemsMap = $cartData['cartItems']->mapWithKeys(function ($i) {
            return [
                $i['key'] => [
                    'key' => $i['key'],
                    'quantity' => $i['quantity'],
                    'unitPrice' => number_format($i['effectiveUnitPriceCents'] / 100, 2),
                    'lineTotal' => number_format($i['lineTotalCents'] / 100, 2),
                    'hasOffer' => ! empty($i['appliedOffer']),
                    'offerTitle' => $i['appliedOffer']?->title ?: (! empty($i['appliedOffer']) ? 'Multi-Buy '.(int) $i['appliedOffer']->discount_percentage.'% OFF' : null),
                ],
            ];
        });

        if ($request->expectsJson() || $request->ajax()) {
            $item = $cartData['cartItems']->firstWhere('key', $cartKey);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
                'cartKey' => $cartKey,
                'quantity' => $item ? $item['quantity'] : 0,
                'cartCount' => $cartData['cartItemCount'],
                'items' => $itemsMap,
                'item' => $item ? [
                    'key' => $item['key'],
                    'quantity' => $item['quantity'],
                    'unitPrice' => number_format($item['effectiveUnitPriceCents'] / 100, 2),
                    'lineTotal' => number_format($item['lineTotalCents'] / 100, 2),
                    'hasOffer' => ! empty($item['appliedOffer']),
                    'offerTitle' => $item['appliedOffer']?->title ?: (! empty($item['appliedOffer']) ? 'Multi-Buy '.(int) $item['appliedOffer']->discount_percentage.'% OFF' : null),
                ] : null,
                'subtotal' => number_format($cartData['cartSubtotalCents'] / 100, 2),
                'savings' => number_format($cartData['cartSavingsCents'] / 100, 2),
                'hasSavings' => $cartData['cartSavingsCents'] > 0,
                'shipping' => $shippingFeeCents === 0 ? 'FREE' : '£'.number_format($shippingFeeCents / 100, 2),
                'total' => number_format(($cartData['cartSubtotalCents'] + $shippingFeeCents) / 100, 2),
                'isEmpty' => $cartData['cartItems']->isEmpty(),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Cart updated.');
    }

    public function destroy(Request $request, Product $product, StorefrontCart $cartService): Response
    {
        $cart = StorefrontCart::sanitizeSessionCart($request);
        $cartKey = $request->input('cart_key')
            ?? ($request->filled('variation_id') ? "{$product->id}:{$request->input('variation_id')}" : (string) $product->id);

        unset($cart[$cartKey]);
        // Also fallback unset numeric ID if exact key not found
        if (! isset($cart[$cartKey]) && isset($cart[$product->id])) {
            unset($cart[$product->id]);
        }

        $request->session()->put('cart', $cart);
        $cart = StorefrontCart::sanitizeSessionCart($request);
        $cartData = $cartService->contents($request);
        $shippingFeeCents = $cartService->shippingFeeCents($cartData['cartSubtotalCents']);

        $itemsMap = $cartData['cartItems']->mapWithKeys(function ($i) {
            return [
                $i['key'] => [
                    'key' => $i['key'],
                    'quantity' => $i['quantity'],
                    'unitPrice' => number_format($i['effectiveUnitPriceCents'] / 100, 2),
                    'lineTotal' => number_format($i['lineTotalCents'] / 100, 2),
                    'hasOffer' => ! empty($i['appliedOffer']),
                    'offerTitle' => $i['appliedOffer']?->title ?: (! empty($i['appliedOffer']) ? 'Multi-Buy '.(int) $i['appliedOffer']->discount_percentage.'% OFF' : null),
                ],
            ];
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart.',
                'cartKey' => $cartKey,
                'cartCount' => $cartData['cartItemCount'],
                'items' => $itemsMap,
                'subtotal' => number_format($cartData['cartSubtotalCents'] / 100, 2),
                'savings' => number_format($cartData['cartSavingsCents'] / 100, 2),
                'hasSavings' => $cartData['cartSavingsCents'] > 0,
                'shipping' => $shippingFeeCents === 0 ? 'FREE' : '£'.number_format($shippingFeeCents / 100, 2),
                'total' => number_format(($cartData['cartSubtotalCents'] + $shippingFeeCents) / 100, 2),
                'isEmpty' => $cartData['cartItems']->isEmpty(),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Product removed from cart.');
    }
}
