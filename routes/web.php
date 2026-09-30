<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/search/live', [StorefrontController::class, 'liveSearch'])->name('products.live-search');
Route::get('/shop-category', [StorefrontController::class, 'category'])->name('shop.category');
Route::get('/product', [StorefrontController::class, 'featuredProduct'])->name('products.index');
Route::get('/product/{product:slug}', [StorefrontController::class, 'product'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product:slug}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product:slug}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/{product:slug}', [WishlistController::class, 'store'])->name('wishlist.store');
Route::delete('/wishlist/{product:slug}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/orders/{order:uuid}', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
