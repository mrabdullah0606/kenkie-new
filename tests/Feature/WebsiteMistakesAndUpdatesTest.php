<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Models\ProductVariation;
use App\Models\User;

test('product page renders variations and multi-buyer offers dynamically', function () {
    $category = Category::factory()->create(['name' => 'Fashion & Apparel', 'slug' => 'fashion-apparel']);
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Designer Cotton Shirt',
        'price' => 50.00,
        'stock' => 30,
        'is_active' => true,
    ]);

    $variation1 = ProductVariation::create([
        'product_id' => $product->id,
        'name' => 'Medium / Blue',
        'sku' => 'SHIRT-M-BLU',
        'sale_price' => 45.00,
        'regular_price' => 55.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $variation2 = ProductVariation::create([
        'product_id' => $product->id,
        'name' => 'Large / Blue',
        'sku' => 'SHIRT-L-BLU',
        'sale_price' => 48.00,
        'regular_price' => 60.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    $offer = ProductOffer::create([
        'product_id' => $product->id,
        'title' => 'Buy 2 Save 10%',
        'min_quantity' => 2,
        'discount_percentage' => 10.00,
        'badge_label' => 'POPULAR',
        'is_active' => true,
    ]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk();
    $response->assertSee('Medium / Blue');
    $response->assertSee('Large / Blue');
    $response->assertSee('Buy 2 Save 10%');
    $response->assertSee('POPULAR');
    $response->assertSee('selectedVariationInput');
});

test('storefront cart and checkout properly process product variations and pricing', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Winter Jacket',
        'price' => 100.00,
        'stock' => 20,
        'is_active' => true,
    ]);

    $variation = ProductVariation::create([
        'product_id' => $product->id,
        'name' => 'Large / Black',
        'sku' => 'JKT-L-BLK',
        'sale_price' => 85.00,
        'regular_price' => 110.00,
        'stock' => 8,
        'is_active' => true,
    ]);

    // Add variation to cart
    $response = $this->post(route('cart.store', $product->slug), [
        'quantity' => 2,
        'variation_id' => $variation->id,
    ]);

    $response->assertRedirect(route('cart.index'));
    $response->assertSessionHas('cart', [
        "{$product->id}:{$variation->id}" => 2,
    ]);

    // Cart index renders variation name and pricing
    $cartResponse = $this->get(route('cart.index'));
    $cartResponse->assertOk();
    $cartResponse->assertSee('Winter Jacket (Large / Black)');
    $cartResponse->assertSee('85.00');

    // Checkout creates order with variation details and decrements variation stock
    $checkoutResponse = $this->actingAs($user)
        ->withSession(['cart' => ["{$product->id}:{$variation->id}" => 2]])
        ->post(route('checkout.store'), [
            'customer_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '07123456789',
            'address_line' => '12 High Street',
            'city' => 'London',
            'region' => 'Greater London',
            'postal_code' => 'SW1A 1AA',
            'country' => 'United Kingdom',
            'payment_method' => 'cash_on_delivery',
        ]);

    $checkoutResponse->assertSessionHasNoErrors();

    $order = Order::latest()->first();
    expect($order)->not->toBeNull()
        ->and((float) $order->subtotal)->toEqual(170.00);

    $item = $order->items->first();
    expect($item->product_name)->toBe('Winter Jacket (Large / Black)')
        ->and($item->sku)->toBe('JKT-L-BLK')
        ->and((float) $item->unit_price)->toEqual(85.00)
        ->and($item->quantity)->toBe(2);

    expect($variation->fresh()->stock)->toBe(6)
        ->and($product->fresh()->stock)->toBe(18);
});

test('admin dashboard calculates comprehensive analytics and metrics', function () {
    $admin = User::factory()->admin()->create();

    $order1 = Order::factory()->create([
        'total' => 150.00,
        'status' => 'completed',
        'created_at' => now(),
    ]);

    $order2 = Order::factory()->create([
        'total' => 75.00,
        'status' => 'pending',
        'created_at' => now(),
    ]);

    OrderItem::create([
        'order_id' => $order1->id,
        'product_name' => 'Ergonomic Desk',
        'sku' => 'DSK-001',
        'unit_price' => 150.00,
        'quantity' => 1,
        'line_total' => 150.00,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('Executive Dashboard');
    $response->assertSee('TOTAL REVENUE');
    $response->assertSee('AVG ORDER VALUE (AOV)');
    $response->assertSee('Growth Trend');
    $response->assertSee('revenueTrendChart');
    $response->assertSee('orderStatusChart');
    $response->assertSee('Top Selling Products');
    $response->assertSee('Ergonomic Desk');
});

test('category system supports parent and sub-category relationships', function () {
    $mainCat = Category::factory()->create([
        'name' => 'Home & Living',
        'slug' => 'home-living',
        'parent_id' => null,
    ]);

    $subCat = Category::factory()->create([
        'name' => 'Kitchen Dining',
        'slug' => 'kitchen-dining',
        'parent_id' => $mainCat->id,
    ]);

    $product = Product::factory()->create([
        'category_id' => $subCat->id,
        'name' => 'Ceramic Bowl Set',
        'is_active' => true,
    ]);

    expect($mainCat->children)->toHaveCount(1)
        ->and($subCat->parent->id)->toBe($mainCat->id);

    // Filtering by main category includes products in its subcategories
    $response = $this->get(route('shop.category', ['category' => $mainCat->slug]));
    $response->assertOk();
    $response->assertSee('Ceramic Bowl Set');

    // Filtering by subcategory directly also works
    $subResponse = $this->get(route('shop.category', ['category' => $subCat->slug]));
    $subResponse->assertOk();
    $subResponse->assertSee('Ceramic Bowl Set');
});
