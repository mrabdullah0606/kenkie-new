<?php

namespace App\Http\Controllers;

use App\Models\BankOffer;
use App\Models\PromotionalPopup;
use App\Services\StorefrontCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Apply a coupon / promo code to the cart session.
     */
    public function apply(Request $request, StorefrontCart $cartService): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $code = strtoupper(trim($validated['code']));
        $cartData = $cartService->contents($request);
        $subtotalCents = $cartData['cartSubtotalCents'];

        if ($subtotalCents <= 0) {
            $msg = 'Your basket is empty. Please add items to your cart before applying a coupon.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        // 1. Check active Promotional Popups
        $popup = PromotionalPopup::query()
            ->where('is_active', true)
            ->whereNotNull('discount_code')
            ->whereRaw('UPPER(discount_code) = ?', [$code])
            ->latest()
            ->first();

        $bankOffer = null;
        if (! $popup) {
            // 2. Check active Bank & Wallet Offers
            $bankOffer = BankOffer::query()
                ->where('is_active', true)
                ->whereRaw('UPPER(code) = ?', [$code])
                ->latest()
                ->first();
        }

        if (! $popup && ! $bankOffer) {
            $msg = "Invalid or expired coupon code '{$code}'. Please check the code and try again.";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        if ($popup) {
            $minSpend = (float) ($popup->min_order_amount ?? 0);
            $minSpendCents = (int) round($minSpend * 100);

            if ($minSpendCents > 0 && $subtotalCents < $minSpendCents) {
                $msg = "Coupon '{$code}' requires a minimum basket spend of £".number_format($minSpend, 2).'.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return back()->with('error', $msg);
            }

            $discountCents = $popup->calculateDiscountCents($subtotalCents);
            $label = ($popup->discount_type ?? 'percentage') === 'fixed'
                ? '£'.number_format((float) $popup->discount_value, 2).' OFF'
                : (float) $popup->discount_value.'% OFF';

            $couponData = [
                'code' => $code,
                'source' => 'popup',
                'title' => $popup->title,
                'label' => $label,
                'discount_type' => $popup->discount_type ?? 'percentage',
                'discount_value' => (float) ($popup->discount_value ?? 10.00),
                'min_order_amount' => $minSpend,
                'discount_cents' => $discountCents,
            ];
        } else {
            // Bank Offer (extract percentage or default 10%)
            preg_match('/(\d+(?:\.\d+)?)\s*%/i', (string) $bankOffer->title, $matches);
            $pct = ! empty($matches[1]) ? (float) $matches[1] : 10.00;
            $discountCents = (int) round($subtotalCents * ($pct / 100));

            $couponData = [
                'code' => $code,
                'source' => 'bank_offer',
                'title' => $bankOffer->title,
                'label' => $pct.'% OFF',
                'discount_type' => 'percentage',
                'discount_value' => $pct,
                'min_order_amount' => 0.00,
                'discount_cents' => $discountCents,
            ];
        }

        $request->session()->put('applied_coupon', $couponData);

        $shippingFeeCents = $cartData['shippingFeeCents'] ?? $cartService->shippingFeeCents($subtotalCents, $cartData['cartItems'] ?? null);
        $totalCents = max(0, $subtotalCents - $discountCents + $shippingFeeCents);

        $successMsg = "Coupon '{$code}' ({$couponData['label']}) applied successfully!";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'hasCoupon' => true,
                'couponDiscount' => '£'.number_format($discountCents / 100, 2),
                'discount' => number_format($discountCents / 100, 2),
                'coupon' => [
                    'code' => $code,
                    'label' => $couponData['label'],
                    'discountFormatted' => '£'.number_format($discountCents / 100, 2),
                    'discountCents' => $discountCents,
                ],
                'subtotal' => number_format($subtotalCents / 100, 2),
                'shipping' => $shippingFeeCents === 0 ? 'FREE' : '£'.number_format($shippingFeeCents / 100, 2),
                'total' => number_format($totalCents / 100, 2),
            ]);
        }

        return back()->with('status', $successMsg);
    }

    /**
     * Remove applied coupon from session.
     */
    public function remove(Request $request, StorefrontCart $cartService): JsonResponse|RedirectResponse
    {
        $request->session()->forget('applied_coupon');
        $cartData = $cartService->contents($request);
        $subtotalCents = $cartData['cartSubtotalCents'];
        $shippingFeeCents = $cartData['shippingFeeCents'] ?? $cartService->shippingFeeCents($subtotalCents, $cartData['cartItems'] ?? null);
        $totalCents = max(0, $subtotalCents + $shippingFeeCents);

        $msg = 'Coupon removed.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'hasCoupon' => false,
                'couponDiscount' => null,
                'discount' => '0.00',
                'subtotal' => number_format($subtotalCents / 100, 2),
                'shipping' => $shippingFeeCents === 0 ? 'FREE' : '£'.number_format($shippingFeeCents / 100, 2),
                'total' => number_format($totalCents / 100, 2),
            ]);
        }

        return back()->with('status', $msg);
    }
}
