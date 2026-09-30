<?php

use App\Models\User;

it('renders the storefront pages with their shared public assets', function (string $path, string $view, string $title) {
    $this->get($path)
        ->assertOk()
        ->assertViewIs($view)
        ->assertSee("<title>$title</title>", false)
        ->assertSee(asset('assets/css/vendors/bootstrap.css'), false);
})->with([
    'home' => ['/', 'website.pages.home', 'Home'],
    'shop category' => ['/shop-category', 'website.pages.category', 'Shop'],
    'cart' => ['/cart', 'website.pages.cart', 'Your cart'],
    'wishlist' => ['/wishlist', 'website.pages.wishlist', 'Wishlist'],
]);

it('requires authentication for the admin dashboard', function () {
    $this->get('/admin')->assertRedirect('/login');
});

it('renders the admin dashboard for authenticated users', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertOk()
        ->assertViewIs('admin.dashboard')
        ->assertSee('Admin dashboard');
});
