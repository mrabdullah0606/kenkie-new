<?php

use App\Http\Controllers\Admin\BankOfferController as AdminBankOfferController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
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

Route::get('/about-us', [StorefrontController::class, 'about'])->name('about');
Route::get('/contact-us', [StorefrontController::class, 'contact'])->name('contact.us');
Route::get('/privacy-policy', [StorefrontController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/return-policy', [StorefrontController::class, 'returnPolicy'])->name('return.policy');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product:slug}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product:slug}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/{product:slug}', [WishlistController::class, 'store'])->name('wishlist.store');
Route::delete('/wishlist/{product:slug}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('users', AdminUserController::class)->except('show');
    Route::get('/home-content', [AdminHomeController::class, 'index'])->name('home.index');
    Route::put('/home-content/hero', [AdminHomeController::class, 'updateHero'])->name('home.hero');
    Route::post('/home-content/banners', [AdminHomeController::class, 'storeBanner'])->name('home.banners.store');
    Route::put('/home-content/banners/{banner}', [AdminHomeController::class, 'updateBanner'])->name('home.banners.update');
    Route::delete('/home-content/banners/{banner}', [AdminHomeController::class, 'destroyBanner'])->name('home.banners.destroy');
    Route::resource('offers', AdminBankOfferController::class)->except('show');
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
});

use App\Http\Controllers\UserDashboardController;

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/stripe/success/{order:uuid}', [CheckoutController::class, 'stripeSuccess'])->name('checkout.stripe.success');
    Route::get('/checkout/stripe/cancel/{order:uuid}', [CheckoutController::class, 'stripeCancel'])->name('checkout.stripe.cancel');
    Route::get('/orders/{order:uuid}', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/account', [UserDashboardController::class, 'index'])->name('account.index');
    Route::get('/account/orders/{uuid}', [UserDashboardController::class, 'showOrder'])->name('account.orders.show');
    Route::patch('/account/profile', [UserDashboardController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [UserDashboardController::class, 'updatePassword'])->name('account.password.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
