<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

it('renders database products on home category and product pages', function () {
    $category = Category::factory()->create(['name' => 'Fresh Food', 'slug' => 'fresh-food']);
    $product = Product::factory()->for($category)->create([
        'name' => 'Garden Tomatoes',
        'slug' => 'garden-tomatoes',
        'price' => 4.5,
        'is_featured' => true,
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('Garden Tomatoes');

    $this->get('/shop-category?category=fresh-food')
        ->assertOk()
        ->assertSee('Garden Tomatoes');

    $this->get(route('products.show', $product->slug))
        ->assertOk()
        ->assertSee('Garden Tomatoes')
        ->assertSee('$4.50');

    $this->get(route('products.index'))
        ->assertRedirect(route('products.show', $product->slug));
});

it('filters shop products by multiple categories, price range, and sort', function () {
    $drinks = Category::factory()->create(['name' => 'Beverages', 'slug' => 'beverages']);
    $bakery = Category::factory()->create(['name' => 'Bakery', 'slug' => 'bakery']);

    $cheapDrink = Product::factory()->for($drinks)->create(['name' => 'Iced Tea', 'price' => 2.50]);
    $expensiveDrink = Product::factory()->for($drinks)->create(['name' => 'Premium Coffee', 'price' => 15.00]);
    $bread = Product::factory()->for($bakery)->create(['name' => 'Fresh Bread', 'price' => 4.00]);

    // Test category checkbox array
    $this->get('/shop-category?categories[]='.$drinks->slug)
        ->assertOk()
        ->assertSee('Iced Tea')
        ->assertSee('Premium Coffee')
        ->assertDontSee('Fresh Bread');

    // Test price range filter
    $this->get('/shop-category?min_price=1.00&max_price=5.00')
        ->assertOk()
        ->assertSee('Iced Tea')
        ->assertSee('Fresh Bread')
        ->assertDontSee('Premium Coffee');

    // Test sorting low to high
    $this->get('/shop-category?sort=low')
        ->assertOk()
        ->assertSee('Iced Tea');
});

it('updates the guest cart and calculates totals from catalog prices', function () {
    $product = Product::factory()->create(['price' => 12.5, 'stock' => 5]);

    $this->post(route('cart.store', $product->slug), ['quantity' => 2])
        ->assertRedirect(route('cart.index'));

    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee('$25.00');

    $this->patch(route('cart.update', $product->slug), ['quantity' => 3])
        ->assertRedirect(route('cart.index'));

    $this->get(route('cart.index'))->assertSee('$37.50');

    $this->delete(route('cart.destroy', $product->slug))
        ->assertRedirect(route('cart.index'));

    $this->get(route('cart.index'))->assertSee('Your cart is empty.');
});

it('rejects a cart quantity greater than available stock', function () {
    $product = Product::factory()->create(['stock' => 2]);

    $this->from(route('products.show', $product->slug))
        ->post(route('cart.store', $product->slug), ['quantity' => 3])
        ->assertSessionHasErrors('quantity');

    expect(session('cart', []))->toBeEmpty();
});

it('adds and removes products from the guest wishlist', function () {
    $product = Product::factory()->create();

    $this->post(route('wishlist.store', $product->slug));

    $this->get(route('wishlist.index'))
        ->assertOk()
        ->assertSee($product->name);

    $this->delete(route('wishlist.destroy', $product->slug));

    $this->get(route('wishlist.index'))->assertSee('Your wishlist is empty.');
});

it('redirects guests from checkout to login page', function () {
    $this->get(route('checkout.index'))->assertRedirect(route('login'));
});

it('places an order using server prices and decrements stock for authenticated user', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 12.5, 'stock' => 5]);

    $this->actingAs($user)->post(route('cart.store', $product->slug), ['quantity' => 2]);

    $response = $this->actingAs($user)->post(route('checkout.store'), [
        'customer_name' => 'Sam Shopper',
        'email' => 'sam@example.test',
        'phone' => '555-0100',
        'address_line' => '10 Market Street',
        'city' => 'Springfield',
        'region' => 'IL',
        'postal_code' => '62701',
        'country' => 'United States',
        'payment_method' => 'cash_on_delivery',
    ]);

    $order = Order::query()->firstOrFail();

    $response->assertRedirect(route('orders.confirmation', $order->uuid));
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'user_id' => $user->id,
        'subtotal' => '25.00',
        'shipping_fee' => '8.95',
        'total' => '33.95',
    ]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'line_total' => '25.00',
    ]);
    expect($product->fresh()->stock)->toBe(3);
    $this->actingAs($user)->get(route('cart.index'))->assertSee('Your cart is empty.');

    $this->actingAs($user)->get(route('orders.confirmation', $order->uuid))
        ->assertOk()
        ->assertSee('Sam Shopper')
        ->assertSee($product->name);
});

it('redirects checkout to the cart when there are no items for authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('checkout.index'))->assertRedirect(route('cart.index'));
});

it('allows an authenticated dashboard admin to create catalog products', function () {
    $category = Category::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Market Apples',
            'slug' => 'market-apples',
            'sku' => 'APP-001',
            'description' => 'Crisp local apples.',
            'price' => '3.25',
            'stock' => 20,
            'unit' => '500 g',
            'image' => 'assets/images/vegetable/product/1.png',
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('products', [
        'slug' => 'market-apples',
        'category_id' => $category->id,
    ]);
});

it('allows an authenticated dashboard admin to update and delete products', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->for($category)->create();

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee($product->name);

    $this->put(route('admin.products.update', $product), [
        'category_id' => $category->id,
        'name' => 'Updated Market Apples',
        'slug' => 'updated-market-apples',
        'sku' => 'APP-UPDATED',
        'description' => 'Updated description.',
        'price' => '4.10',
        'stock' => 15,
        'unit' => '750 g',
        'image' => 'assets/images/vegetable/product/1.png',
        'is_active' => '1',
    ])
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Market Apples',
        'price' => '4.10',
    ]);

    $this->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

it('returns live search JSON suggestions matching query', function () {
    $category = Category::factory()->create(['name' => 'Garden Tools']);
    $product1 = Product::factory()->for($category)->create(['name' => 'Cordless Lawn Mower', 'price' => 199.99, 'is_active' => true]);
    $product2 = Product::factory()->for($category)->create(['name' => 'Pruning Shears', 'price' => 24.50, 'is_active' => true]);
    $inactive = Product::factory()->for($category)->create(['name' => 'Lawn Fertilizer', 'price' => 12.00, 'is_active' => false]);

    $response = $this->getJson(route('products.live-search', ['q' => 'Lawn']))
        ->assertOk()
        ->assertJsonStructure([
            'products' => [
                '*' => ['id', 'name', 'slug', 'url', 'price', 'image', 'category_name', 'stock', 'is_in_stock'],
            ],
            'total',
            'viewAllUrl',
        ]);

    $data = $response->json();
    expect($data['total'])->toBe(1);
    expect($data['products'][0]['name'])->toBe('Cordless Lawn Mower');
});

it('allows customer to access user dashboard and view their orders and tracking', function () {
    $customer = User::factory()->customer()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $category = Category::factory()->create();
    $product = Product::factory()->for($category)->create(['name' => 'Ceramic Planter', 'price' => 25.00]);

    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'customer_name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'status' => 'processing',
        'subtotal' => 25.00,
        'shipping_fee' => 0.00,
        'total' => 25.00,
    ]);

    $order->items()->create([
        'product_id' => $product->id,
        'product_name' => 'Ceramic Planter',
        'sku' => $product->sku,
        'unit_price' => 25.00,
        'quantity' => 1,
        'line_total' => 25.00,
    ]);

    // Dashboard index
    $this->actingAs($customer)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewIs('website.content.user-dashboard')
        ->assertSee('Jane Doe')
        ->assertSee('Ceramic Planter')
        ->assertSee('PROCESSING');

    // Order detail & tracking
    $this->actingAs($customer)
        ->get(route('account.orders.show', $order->uuid))
        ->assertOk()
        ->assertViewIs('website.content.order-details')
        ->assertSee($order->uuid)
        ->assertSee('Ceramic Planter')
        ->assertSee('processing');

    // Update customer profile
    $this->actingAs($customer)
        ->patch(route('account.profile.update'), [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $customer->refresh();
    expect($customer->name)->toBe('Jane Smith');
    expect($customer->email)->toBe('jane.smith@example.com');
});

it('loads static informational pages successfully', function () {
    $this->get(route('about'))->assertOk()->assertSee('A Company You Can Trust')->assertSee('Ashfaq Ahmad');
    $this->get(route('contact.us'))->assertOk()->assertSee('CONTACT US')->assertSee('info@kenkie.com');
    $this->get(route('privacy.policy'))->assertOk()->assertSee('Privacy Policy')->assertSee('KENKIE Ltd');
    $this->get(route('return.policy'))->assertOk()->assertSee('Returns and Refund Policy')->assertSee('14 working days');
});

it('places an order using stripe payment method', function () {
    config(['services.stripe.secret' => 'sk_test_placeholder']);

    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 20.0, 'stock' => 10]);

    $response = $this->actingAs($user)->withSession(['cart' => [$product->id => 1]])->post(route('checkout.store'), [
        'customer_name' => 'Stripe Buyer',
        'email' => 'buyer@example.test',
        'phone' => '07898346397',
        'address_line' => '51 Bescot Road',
        'city' => 'Walsall',
        'region' => 'West Midlands',
        'postal_code' => 'WS2 9AD',
        'country' => 'United Kingdom',
        'payment_method' => 'stripe',
    ]);

    $order = Order::query()->where('customer_name', 'Stripe Buyer')->firstOrFail();

    expect($order->payment_method)->toBe('stripe');
    expect($order->payment_status)->toBe('paid');
    $response->assertRedirect(route('orders.confirmation', $order->uuid));
});
