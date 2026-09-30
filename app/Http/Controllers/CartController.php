<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        ]);
        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = StorefrontCart::sanitizeSessionCart($request);
        $newQuantity = (int) ($cart[$product->id] ?? 0) + $quantity;

        if ($newQuantity > $product->stock) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->stock} available.",
                ], 422);
            }

            return back()->withErrors(['quantity' => "Only {$product->stock} available."])->withInput();
        }

        $cart[$product->id] = $newQuantity;
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

    public function update(Request $request, Product $product): Response
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:'.$product->stock],
        ]);
        $cart = StorefrontCart::sanitizeSessionCart($request);

        $quantity = (int) $validated['quantity'];
        if ($quantity <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = $quantity;
        }
        $request->session()->put('cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
                'quantity' => $quantity,
                'cartCount' => array_sum($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Cart updated.');
    }

    public function destroy(Request $request, Product $product): Response
    {
        $cart = StorefrontCart::sanitizeSessionCart($request);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart.',
                'cartCount' => array_sum($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Product removed from cart.');
    }
}
