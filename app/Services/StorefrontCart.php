<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StorefrontCart
{
    public const int SHIPPING_FEE_CENTS = 895;

    public const int FREE_SHIPPING_THRESHOLD_CENTS = 5000;

    /**
     * @return array<int, int>
     */
    public static function sanitizeSessionCart(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        if (! is_array($cart) || empty($cart)) {
            $request->session()->put('cart', []);

            return [];
        }

        $quantities = [];
        foreach ($cart as $productId => $quantity) {
            $productId = filter_var($productId, FILTER_VALIDATE_INT);
            $quantity = filter_var($quantity, FILTER_VALIDATE_INT);

            if ($productId && $quantity && $quantity > 0) {
                $quantities[$productId] = $quantity;
            }
        }

        if (empty($quantities)) {
            $request->session()->put('cart', []);

            return [];
        }

        $validProducts = Product::query()
            ->whereKey(array_keys($quantities))
            ->where('is_active', true)
            ->pluck('stock', 'id');

        $sanitized = [];
        foreach ($quantities as $productId => $quantity) {
            if ($validProducts->has($productId)) {
                $stock = (int) $validProducts->get($productId);
                if ($stock > 0) {
                    $sanitized[$productId] = min($quantity, $stock);
                }
            }
        }

        $request->session()->put('cart', $sanitized);

        return $sanitized;
    }

    /**
     * @return array{
     *     cartItems: Collection<int, array{product: Product, quantity: int, lineTotalCents: int}>,
     *     cartItemCount: int,
     *     cartSubtotalCents: int
     * }
     */
    public function contents(Request $request): array
    {
        $quantities = self::sanitizeSessionCart($request);

        $products = Product::query()
            ->whereKey(array_keys($quantities))
            ->get()
            ->keyBy('id');

        $cartItems = collect($quantities)
            ->map(function (int $quantity, int $productId) use ($products): ?array {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $unitPriceCents = (int) round((float) $product->price * 100);

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'lineTotalCents' => $unitPriceCents * $quantity,
                ];
            })
            ->filter()
            ->values();

        return [
            'cartItems' => $cartItems,
            'cartItemCount' => $cartItems->sum('quantity'),
            'cartSubtotalCents' => $cartItems->sum('lineTotalCents'),
        ];
    }

    public function shippingFeeCents(int $subtotalCents): int
    {
        return $subtotalCents >= self::FREE_SHIPPING_THRESHOLD_CENTS ? 0 : self::SHIPPING_FEE_CENTS;
    }
}
