<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                if (! $view->offsetExists('categories')) {
                    $view->with('categories', Category::query()
                        ->where('is_active', true)
                        ->withCount('products')
                        ->orderBy('position')
                        ->get()
                    );
                }

                $cart = session('cart', []);
                $validProductIds = ! empty($cart) && is_array($cart)
                    ? Product::whereIn('id', array_keys($cart))->where('is_active', true)->pluck('id')->all()
                    : [];

                $cartCount = 0;
                foreach ($validProductIds as $validId) {
                    $cartCount += (int) ($cart[$validId] ?? 0);
                }

                $wishlist = session('wishlist', []);
                $validWishlistIds = ! empty($wishlist) && is_array($wishlist)
                    ? Product::whereIn('id', array_values($wishlist))->where('is_active', true)->pluck('id')->all()
                    : [];
                $wishlistCount = count($validWishlistIds);

                $view->with([
                    'headerCategories' => Category::query()
                        ->where('is_active', true)
                        ->orderBy('position')
                        ->get(),
                    'headerCartCount' => $cartCount,
                    'headerWishlistCount' => $wishlistCount,
                ]);
            } catch (\Throwable $e) {
                // Ignore DB connection errors during early bootstrapping / migrations
            }
        });
    }
}
