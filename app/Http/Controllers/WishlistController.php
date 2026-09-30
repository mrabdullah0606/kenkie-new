<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $productIds = array_map('intval', array_keys($request->session()->get('wishlist', [])));

        return view('website.pages.wishlist', [
            'products' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->whereKey($productIds)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request, Product $product): Response
    {
        abort_unless($product->is_active, 404);

        $wishlist = $request->session()->get('wishlist', []);
        $wishlist[$product->id] = true;
        $request->session()->put('wishlist', $wishlist);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist.',
                'wishlistCount' => count($wishlist),
            ]);
        }

        return back()->with('status', 'Product added to wishlist.');
    }

    public function destroy(Request $request, Product $product): Response
    {
        $wishlist = $request->session()->get('wishlist', []);
        unset($wishlist[$product->id]);
        $request->session()->put('wishlist', $wishlist);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist.',
                'wishlistCount' => count($wishlist),
            ]);
        }

        return back()->with('status', 'Product removed from wishlist.');
    }
}
