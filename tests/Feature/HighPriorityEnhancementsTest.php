<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\User;

it('supports category hierarchy with parent and subcategories', function () {
    $admin = User::factory()->admin()->create();

    $mainCategory = Category::create([
        'name' => 'Living Room',
        'slug' => 'living-room',
        'position' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'parent_id' => $mainCategory->id,
            'name' => 'Sofas & Couches',
            'slug' => 'sofas-couches',
            'position' => 2,
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $subCategory = Category::where('slug', 'sofas-couches')->first();

    expect($subCategory)->not->toBeNull()
        ->and($subCategory->parent_id)->toBe($mainCategory->id)
        ->and($subCategory->parent->id)->toBe($mainCategory->id)
        ->and($mainCategory->children)->toHaveCount(1)
        ->and($mainCategory->children->first()->id)->toBe($subCategory->id);

    expect(Category::main()->count())->toBe(1)
        ->and(Category::sub()->count())->toBe(1);
});

it('calculates product discount percentage and manages variations', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    // Store product with regular_price, cost_price, SEO, and variations
    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Velvet Armchair',
            'slug' => 'velvet-armchair',
            'sku' => 'VLV-ARM-01',
            'price' => 80.00,
            'regular_price' => 100.00,
            'cost_price' => 45.00,
            'stock' => 12,
            'unit' => '1 piece',
            'is_active' => '1',
            'meta_title' => 'Comfortable Velvet Armchair',
            'meta_description' => 'Luxury armchair for modern homes',
            'meta_keywords' => 'armchair, velvet, luxury',
            'variations' => [
                [
                    'name' => 'Emerald Green / Large',
                    'color' => 'Emerald Green',
                    'size' => 'Large',
                    'sku' => 'VLV-ARM-GRN-L',
                    'regular_price' => 110.00,
                    'sale_price' => 90.00,
                    'stock' => 5,
                    'is_active' => '1',
                ],
                [
                    'name' => 'Navy Blue / Medium',
                    'color' => 'Navy Blue',
                    'size' => 'Medium',
                    'sku' => 'VLV-ARM-BLU-M',
                    'regular_price' => 95.00,
                    'sale_price' => 80.00,
                    'stock' => 7,
                    'is_active' => '1',
                ],
            ],
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::with('variations')->where('slug', 'velvet-armchair')->first();

    expect($product)->not->toBeNull()
        ->and($product->discount_percentage)->toBe(20)
        ->and((float) $product->cost_price)->toBe(45.00)
        ->and($product->meta_title)->toBe('Comfortable Velvet Armchair')
        ->and($product->variations)->toHaveCount(2);

    $greenVariation = $product->variations->firstWhere('color', 'Emerald Green');
    expect($greenVariation->discount_percentage)->toBe(18)
        ->and($greenVariation->effective_price)->toBe(90.0);
});

it('filters low stock products in admin catalog', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Low Stock Product',
        'stock' => 3,
        'is_active' => true,
    ]);

    Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Abundant Product',
        'stock' => 50,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.products.index', ['status' => 'low_stock']))
        ->assertOk();

    $response->assertSee('Low Stock Product');
    $response->assertDontSee('Abundant Product');
});

it('duplicates a product and its variations correctly', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Dining Table',
        'slug' => 'dining-table',
        'sku' => 'TAB-DIN-01',
        'price' => 250.00,
        'stock' => 10,
    ]);

    ProductVariation::create([
        'product_id' => $product->id,
        'name' => 'Oak 6-Seater',
        'sku' => 'TAB-DIN-OAK-6',
        'sale_price' => 250.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.products.duplicate', $product))
        ->assertRedirect();

    $duplicate = Product::with('variations')->where('name', 'Dining Table (Copy)')->first();

    expect($duplicate)->not->toBeNull()
        ->and($duplicate->id)->not->toBe($product->id)
        ->and($duplicate->slug)->not->toBe($product->slug)
        ->and($duplicate->sku)->not->toBe($product->sku)
        ->and($duplicate->is_active)->toBeFalse()
        ->and($duplicate->variations)->toHaveCount(1)
        ->and($duplicate->variations->first()->product_id)->toBe($duplicate->id);
});

it('toggles product active status', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.products.toggle-status', $product))
        ->assertRedirect();

    expect($product->fresh()->is_active)->toBeFalse();

    $this->actingAs($admin)
        ->patch(route('admin.products.toggle-status', $product))
        ->assertRedirect();

    expect($product->fresh()->is_active)->toBeTrue();
});

it('manages order fulfillment status, auto timestamps, courier tracking, and sticky notes', function () {
    $admin = User::factory()->admin()->create();

    $order = Order::factory()->create([
        'status' => 'pending',
        'customer_name' => 'Alice Smith',
        'email' => 'alice@example.com',
        'phone' => '+44 7123 456789',
        'address_line' => '10 Downing Street',
        'city' => 'London',
        'postal_code' => 'SW1A 2AA',
        'country' => 'United Kingdom',
    ]);

    // Check formatted order ID
    expect($order->formatted_order_id)->toStartWith('KNK-');

    // Update status to shipped (should set shipped_at)
    $this->actingAs($admin)
        ->patch(route('admin.orders.update-status', $order), [
            'status' => 'shipped',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    $order->refresh();
    expect($order->status)->toBe('shipped')
        ->and($order->shipped_at)->not->toBeNull();

    // Update courier tracking information
    $this->actingAs($admin)
        ->patch(route('admin.orders.update-tracking', $order), [
            'courier_name' => 'Royal Mail Tracked 24',
            'tracking_number' => 'RM-123456789GB',
            'tracking_url' => 'https://www.royalmail.com/track-your-item#/tracking-results/RM-123456789GB',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    $order->refresh();
    expect($order->courier_name)->toBe('Royal Mail Tracked 24')
        ->and($order->tracking_number)->toBe('RM-123456789GB')
        ->and($order->tracking_url)->toContain('RM-123456789GB');

    // Save sticky private admin notes
    $this->actingAs($admin)
        ->patch(route('admin.orders.update-notes', $order), [
            'admin_notes' => 'Customer called to request gift packaging.',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    expect($order->fresh()->admin_notes)->toBe('Customer called to request gift packaging.');

    // Update shipping address
    $this->actingAs($admin)
        ->patch(route('admin.orders.update-shipping-address', $order), [
            'customer_name' => 'Alice Jones',
            'email' => 'alice.jones@example.com',
            'phone' => '+44 7999 888777',
            'address_line' => '221B Baker Street',
            'city' => 'London',
            'region' => 'Greater London',
            'postal_code' => 'NW1 6XE',
            'country' => 'United Kingdom',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    $order->refresh();
    expect($order->customer_name)->toBe('Alice Jones')
        ->and($order->email)->toBe('alice.jones@example.com')
        ->and($order->address_line)->toBe('221B Baker Street')
        ->and($order->postal_code)->toBe('NW1 6XE');
});
