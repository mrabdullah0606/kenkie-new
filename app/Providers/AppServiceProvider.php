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
                        ->with(['parent', 'children' => fn ($q) => $q->where('is_active', true)->withCount('products')])
                        ->withCount('products')
                        ->orderBy('position')
                        ->get()
                    );
                }

                $cart = session('cart', []);
                $cartCount = 0;
                if (is_array($cart)) {
                    foreach ($cart as $key => $qty) {
                        $cartCount += max(0, (int) $qty);
                    }
                }

                $wishlist = session('wishlist', []);
                $validWishlistIds = ! empty($wishlist) && is_array($wishlist)
                    ? Product::whereIn('id', array_values($wishlist))->where('is_active', true)->pluck('id')->all()
                    : [];
                $wishlistCount = count($validWishlistIds);

                $view->with([
                    'headerCategories' => Category::query()
                        ->whereNull('parent_id')
                        ->where('is_active', true)
                        ->with(['children' => fn ($q) => $q->where('is_active', true)->withCount('products')->orderBy('position')])
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
