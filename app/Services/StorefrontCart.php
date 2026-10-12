<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StorefrontCart
{
    public const int SHIPPING_FEE_CENTS = 895;

    public const int FREE_SHIPPING_THRESHOLD_CENTS = 5000;

    /**
     * @return array<string|int, int>
     */
    public static function sanitizeSessionCart(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        if (! is_array($cart) || empty($cart)) {
            $request->session()->put('cart', []);

            return [];
        }

        $parsed = [];
        $productIds = [];
        $variationIds = [];

        foreach ($cart as $cartKey => $quantity) {
            $cartKey = trim((string) $cartKey);
            $qty = filter_var($quantity, FILTER_VALIDATE_INT);

            if ($cartKey === '' || ! $qty || $qty <= 0) {
                continue;
            }

            if (str_contains($cartKey, ':')) {
                [$pId, $vId] = explode(':', $cartKey, 2);
                $pId = filter_var($pId, FILTER_VALIDATE_INT);
                $vId = filter_var($vId, FILTER_VALIDATE_INT);

                if ($pId && $vId) {
                    $parsed[$cartKey] = [
                        'product_id' => (int) $pId,
                        'variation_id' => (int) $vId,
                        'quantity' => (int) $qty,
                    ];
                    $productIds[] = (int) $pId;
                    $variationIds[] = (int) $vId;
                }
            } else {
                $pId = filter_var($cartKey, FILTER_VALIDATE_INT);
                if ($pId) {
                    $parsed[$cartKey] = [
                        'product_id' => (int) $pId,
                        'variation_id' => null,
                        'quantity' => (int) $qty,
                    ];
                    $productIds[] = (int) $pId;
                }
            }
        }

        if (empty($parsed)) {
            $request->session()->put('cart', []);

            return [];
        }

        $products = Product::query()
            ->whereIn('id', array_unique($productIds))
            ->where('is_active', true)
            ->with(['activeVariations'])
            ->get()
            ->keyBy('id');

        $sanitized = [];
        foreach ($parsed as $cartKey => $item) {
            $product = $products->get($item['product_id']);
            if (! $product) {
                continue;
            }

            $maxStock = (int) $product->stock;

            if (! empty($item['variation_id'])) {
                $variation = $product->activeVariations->firstWhere('id', $item['variation_id']);
                if (! $variation || ! $variation->is_active) {
                    continue;
                }
                $maxStock = (int) $variation->stock;
            }

            if ($maxStock > 0) {
                $sanitized[$cartKey] = min($item['quantity'], $maxStock);
            }
        }

        $request->session()->put('cart', $sanitized);

        return $sanitized;
    }

    /**
     * @return array{
     *     cartItems: Collection<int, array{
     *         key: string,
     *         product: Product,
     *         variation: ?ProductVariation,
     *         variationId: ?int,
     *         displayName: string,
     *         displaySku: ?string,
     *         displayImage: ?string,
     *         maxStock: int,
     *         quantity: int,
     *         unitPriceCents: int,
     *         effectiveUnitPriceCents: int,
     *         appliedOffer: mixed,
     *         savedCents: int,
     *         lineTotalCents: int
     *     }>,
     *     cartItemCount: int,
     *     cartSubtotalCents: int,
     *     cartSavingsCents: int
     * }
     */
    public function contents(Request $request): array
    {
        $quantities = self::sanitizeSessionCart($request);

        $productIds = [];
        foreach (array_keys($quantities) as $cartKey) {
            $pId = str_contains((string) $cartKey, ':')
                ? (int) explode(':', (string) $cartKey)[0]
                : (int) $cartKey;
            $productIds[] = $pId;
        }

        $products = Product::query()
            ->whereIn('id', array_unique($productIds))
            ->with(['activeOffers', 'activeVariations', 'category'])
            ->get()
            ->keyBy('id');

        $cartItems = collect($quantities)
            ->map(function (int $quantity, string|int $cartKey) use ($products): ?array {
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
                if (! $product) {
                    return null;
                }

                $variation = null;
                if ($variationId && $product->relationLoaded('activeVariations')) {
                    $variation = $product->activeVariations->firstWhere('id', $variationId);
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
                $savedCents = ($unitPriceCents * $quantity) - $lineTotalCents;

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

                return [
                    'key' => $cartKeyStr,
                    'product' => $product,
                    'variation' => $variation,
                    'variationId' => $variationId,
                    'displayName' => $displayName,
                    'displaySku' => $variation?->sku ?: $product->sku,
                    'displayImage' => $variation?->image ?: $product->image,
                    'maxStock' => $variation ? (int) $variation->stock : (int) $product->stock,
                    'quantity' => $quantity,
                    'unitPriceCents' => $unitPriceCents,
                    'effectiveUnitPriceCents' => $effectiveUnitPriceCents,
                    'appliedOffer' => $appliedOffer,
                    'savedCents' => $savedCents,
                    'lineTotalCents' => $lineTotalCents,
                ];
            })
            ->filter()
            ->values();

        return [
            'cartItems' => $cartItems,
            'cartItemCount' => $cartItems->sum('quantity'),
            'cartSubtotalCents' => $cartItems->sum('lineTotalCents'),
            'cartSavingsCents' => $cartItems->sum('savedCents'),
        ];
    }

    public function shippingFeeCents(int $subtotalCents): int
    {
        return $subtotalCents >= self::FREE_SHIPPING_THRESHOLD_CENTS ? 0 : self::SHIPPING_FEE_CENTS;
    }
}
