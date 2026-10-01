<?php

use App\Models\BankOffer;
use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\HomeSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('denies non-admin customers from accessing admin dashboard and modules', function () {
    $customer = User::factory()->customer()->create();

    $this->actingAs($customer)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

    $this->actingAs($customer)
        ->get(route('admin.products.index'))
        ->assertForbidden();

    $this->actingAs($customer)
        ->get(route('admin.categories.index'))
        ->assertForbidden();

    $this->actingAs($customer)
        ->get(route('admin.orders.index'))
        ->assertForbidden();

    $this->actingAs($customer)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

it('allows admin to manage categories and upload category image', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();

    // Index
    $this->actingAs($admin)
        ->get(route('admin.categories.index'))
        ->assertOk()
        ->assertViewIs('admin.categories.index');

    $file = UploadedFile::fake()->create('category_patio.png', 100, 'image/png');

    // Create & Store with image file
    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Outdoor Living',
            'slug' => 'outdoor-living',
            'image_file' => $file,
            'position' => 3,
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status');

    $category = Category::where('slug', 'outdoor-living')->firstOrFail();
    expect($category->name)->toBe('Outdoor Living');
    expect($category->image)->toStartWith('storage/categories/');

    // Edit & Update
    $this->actingAs($admin)
        ->put(route('admin.categories.update', $category), [
            'name' => 'Outdoor Living & Decor',
            'slug' => 'outdoor-living-decor',
            'image' => 'assets/images/category/1.png',
            'position' => 2,
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $category->refresh();
    expect($category->name)->toBe('Outdoor Living & Decor');

    // Delete
    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'));

    expect(Category::where('id', $category->id)->exists())->toBeFalse();
});

it('allows admin to create products with rich text description and uploaded images', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $mainImage = UploadedFile::fake()->create('product_main.png', 100, 'image/png');
    $gallery1 = UploadedFile::fake()->create('gallery1.png', 100, 'image/png');
    $gallery2 = UploadedFile::fake()->create('gallery2.png', 100, 'image/png');

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Premium Outdoor Sofa',
            'slug' => 'premium-outdoor-sofa',
            'sku' => 'SOF-001',
            'description' => '<h1>Luxury Comfort</h1><p>Weather-resistant <strong>cushions</strong>.</p>',
            'price' => '499.99',
            'stock' => 10,
            'unit' => '1 piece',
            'image_file' => $mainImage,
            'gallery_files' => [$gallery1, $gallery2],
            'is_featured' => '1',
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('status');

    $product = Product::where('slug', 'premium-outdoor-sofa')->firstOrFail();
    expect($product->name)->toBe('Premium Outdoor Sofa');
    expect($product->description)->toContain('<h1>Luxury Comfort</h1>');
    expect($product->image)->toStartWith('storage/products/');
    expect($product->images)->toHaveCount(2);

    // Test removing primary image and keeping only 1 gallery image
    $firstGalleryImage = $product->images[0];
    $this->actingAs($admin)
        ->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => 'Premium Outdoor Sofa Updated',
            'slug' => 'premium-outdoor-sofa',
            'sku' => 'SOF-001',
            'price' => '499.99',
            'stock' => 10,
            'unit' => '1 piece',
            'remove_primary_image' => '1',
            'keep_gallery_images' => [$firstGalleryImage],
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    $product->refresh();
    expect($product->image)->toBeNull();
    expect($product->images)->toHaveCount(1);
    expect($product->images[0])->toBe($firstGalleryImage);
});

it('allows admin to view orders and update fulfillment status', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create(['status' => 'pending']);

    // Orders Index
    $this->actingAs($admin)
        ->get(route('admin.orders.index'))
        ->assertOk()
        ->assertViewIs('admin.orders.index')
        ->assertSee($order->customer_name);

    // Order Show Detail
    $this->actingAs($admin)
        ->get(route('admin.orders.show', $order))
        ->assertOk()
        ->assertViewIs('admin.orders.show')
        ->assertSee($order->customer_name);

    // Update Status
    $this->actingAs($admin)
        ->patch(route('admin.orders.update', $order), [
            'status' => 'completed',
        ])
        ->assertRedirect(route('admin.orders.show', $order));

    $order->refresh();
    expect($order->status)->toBe('completed');
});

it('allows admin to manage users and roles', function () {
    $admin = User::factory()->admin()->create(['email' => 'admin@kenkie.com']);
    $user = User::factory()->customer()->create(['name' => 'Alice Smith', 'email' => 'alice@kenkie.com']);

    // Users Index
    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertViewIs('admin.users.index')
        ->assertSee('Alice Smith');

    // Create User with role
    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Bob Builder',
            'email' => 'bob@kenkie.com',
            'role' => 'admin',
            'password' => 'Password123!',
        ])
        ->assertRedirect(route('admin.users.index'));

    $bob = User::where('email', 'bob@kenkie.com')->firstOrFail();
    expect($bob->role)->toBe('admin');
    expect($bob->isAdmin())->toBeTrue();

    // Update User
    $this->actingAs($admin)
        ->put(route('admin.users.update', $user), [
            'name' => 'Alice Johnson',
            'email' => 'alice.johnson@kenkie.com',
            'role' => 'customer',
        ])
        ->assertRedirect(route('admin.users.index'));

    $user->refresh();
    expect($user->name)->toBe('Alice Johnson');

    // Delete User
    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $user))
        ->assertRedirect(route('admin.users.index'));

    expect(User::where('id', $user->id)->exists())->toBeFalse();
});

it('allows admin to manage homepage hero content and promo banner cards', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();

    // Home Content Index
    $this->actingAs($admin)
        ->get(route('admin.home.index'))
        ->assertOk()
        ->assertViewIs('admin.home.index')
        ->assertSeeText('Homepage Content & Banners');

    $heroImage = UploadedFile::fake()->create('custom_hero.jpg', 100, 'image/jpeg');

    // Update Hero Banner
    $this->actingAs($admin)
        ->put(route('admin.home.hero'), [
            'hero_badge' => 'Exclusive Autumn Deals',
            'hero_title' => 'Modern Living & Essentials Superstore',
            'hero_subtitle' => 'Save up to 50% this weekend',
            'hero_description' => 'Browse top-quality curated collection.',
            'hero_button_text' => 'Shop All Now',
            'hero_button_url' => '/shop-category',
            'hero_image_file' => $heroImage,
        ])
        ->assertRedirect(route('admin.home.index'))
        ->assertSessionHas('status');

    $setting = HomeSetting::getSettings();
    expect($setting->hero_badge)->toBe('Exclusive Autumn Deals');
    expect($setting->hero_title)->toBe('Modern Living & Essentials Superstore');
    expect($setting->hero_image)->toStartWith('storage/banners/');

    // Store Promo Banner Card
    $bannerImage = UploadedFile::fake()->create('promo_tech.jpg', 100, 'image/jpeg');
    $this->actingAs($admin)
        ->post(route('admin.home.banners.store'), [
            'title' => 'Cutting-Edge Electronics',
            'subtitle' => 'Smart Tech 2026',
            'button_text' => 'Explore Tech',
            'button_url' => '/shop-category?category=sound-vision',
            'banner_image_file' => $bannerImage,
            'position' => 0,
        ])
        ->assertRedirect(route('admin.home.index'));

    $banner = HomeBanner::where('title', 'Cutting-Edge Electronics')->firstOrFail();
    expect($banner->subtitle)->toBe('Smart Tech 2026');
    expect($banner->image)->toStartWith('storage/banners/');

    // Verify storefront shows updated hero
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Exclusive Autumn Deals')
        ->assertSee('Modern Living & Essentials Superstore')
        ->assertSee('Cutting-Edge Electronics');
});

it('allows admin to manage bank and wallet offers', function () {
    $admin = User::factory()->admin()->create();

    // Offers Index
    $this->actingAs($admin)
        ->get(route('admin.offers.index'))
        ->assertOk()
        ->assertViewIs('admin.offers.index');

    // Create Bank Offer
    $this->actingAs($admin)
        ->post(route('admin.offers.store'), [
            'title' => 'GET 25% CASHBACK',
            'subtitle' => 'With PayPal & Visa card',
            'validity' => 'Valid for 14 days',
            'code' => 'PAYPAL25',
            'color_theme' => 'theme-2',
            'position' => 1,
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.offers.index'))
        ->assertSessionHas('status');

    $offer = BankOffer::where('code', 'PAYPAL25')->firstOrFail();
    expect($offer->title)->toBe('GET 25% CASHBACK');
    expect($offer->color_theme)->toBe('theme-2');

    // Verify storefront shows the new offer
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('GET 25% CASHBACK')
        ->assertSee('PAYPAL25');
});

it('allows admin to toggle top deal and hot deal on products and renders on home', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Ultimate Smart Blender Pro',
        'slug' => 'ultimate-smart-blender-pro',
        'is_top_deal' => false,
        'is_hot_deal' => false,
    ]);

    // Update product with top deal and hot deal
    $this->actingAs($admin)
        ->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => 'Ultimate Smart Blender Pro',
            'slug' => 'ultimate-smart-blender-pro',
            'sku' => $product->sku,
            'price' => 129.99,
            'stock' => 15,
            'unit' => '1 Unit',
            'is_featured' => '1',
            'is_top_deal' => '1',
            'is_hot_deal' => '1',
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    $product->refresh();
    expect($product->is_top_deal)->toBeTrue();
    expect($product->is_hot_deal)->toBeTrue();

    // Verify storefront renders product in Hot Deal / Special Offer card
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Ultimate Smart Blender Pro')
        ->assertSee('Hot')
        ->assertSee('Deal');
});

it('allows admin to search and filter products by keyword, category, and promotion status', function () {
    $admin = User::factory()->admin()->create();
    $furniture = Category::factory()->create(['name' => 'Living Room Furniture']);
    $electronics = Category::factory()->create(['name' => 'Consumer Electronics']);

    $chair = Product::factory()->create([
        'category_id' => $furniture->id,
        'name' => 'Ergonomic Mesh Chair',
        'sku' => 'CHAIR-MESH-99',
        'is_active' => true,
        'is_featured' => true,
    ]);

    $tv = Product::factory()->create([
        'category_id' => $electronics->id,
        'name' => '4K Ultra OLED TV',
        'sku' => 'TV-OLED-55',
        'is_active' => false,
        'is_featured' => false,
    ]);

    // Search by product name
    $this->actingAs($admin)
        ->get(route('admin.products.index', ['search' => 'Ergonomic']))
        ->assertOk()
        ->assertSee('Ergonomic Mesh Chair')
        ->assertDontSee('4K Ultra OLED TV');

    // Search by SKU
    $this->actingAs($admin)
        ->get(route('admin.products.index', ['search' => 'TV-OLED']))
        ->assertOk()
        ->assertSee('4K Ultra OLED TV')
        ->assertDontSee('Ergonomic Mesh Chair');

    // Filter by Category
    $this->actingAs($admin)
        ->get(route('admin.products.index', ['category_id' => $furniture->id]))
        ->assertOk()
        ->assertSee('Ergonomic Mesh Chair')
        ->assertDontSee('4K Ultra OLED TV');

    // Filter by Status: Active
    $this->actingAs($admin)
        ->get(route('admin.products.index', ['status' => 'active']))
        ->assertOk()
        ->assertSee('Ergonomic Mesh Chair')
        ->assertDontSee('4K Ultra OLED TV');

    // Filter by Status: Inactive/Hidden
    $this->actingAs($admin)
        ->get(route('admin.products.index', ['status' => 'inactive']))
        ->assertOk()
        ->assertSee('4K Ultra OLED TV')
        ->assertDontSee('Ergonomic Mesh Chair');
});
