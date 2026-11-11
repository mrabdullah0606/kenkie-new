 <?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Models\ProductVariation;
use App\Models\PromotionalPopup;
use App\Models\ShippingSetting;
use App\Models\User;
use App\Services\StorefrontCart;
use Illuminate\Support\Facades\Http;

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
        'color' => 'Blue',
        'size' => 'Medium',
        'sku' => 'SHIRT-M-BLU',
        'sale_price' => 45.00,
        'regular_price' => 55.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $variation2 = ProductVariation::create([
        'product_id' => $product->id,
        'name' => 'Large / Blue',
        'color' => 'Blue',
        'size' => 'Large',
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
    $response->assertSee('Choose Color:');
    $response->assertSee('Choose Size:');
    $response->assertSee('Blue');
    $response->assertSee('Medium');
    $response->assertSee('Large');
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
    $cartResponse = $this->withSession(['cart' => ["{$product->id}:{$variation->id}" => 2]])
        ->get(route('cart.index'));
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

test('cart real-time ajax update dynamically recalculates multi-buy savings, line totals, and empty states', function () {
    $product = Product::factory()->create([
        'name' => 'Tailored Australian Merino Wool Overcoat',
        'price' => 210.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $variation = ProductVariation::create([
        'product_id' => $product->id,
        'name' => 'Medium / Camel',
        'color' => 'Camel',
        'size' => 'Medium',
        'sku' => 'COT-CML-M',
        'sale_price' => 210.00,
        'regular_price' => 240.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    ProductOffer::create([
        'product_id' => $product->id,
        'title' => 'Buy 2 Coats - Save 12% Extra',
        'min_quantity' => 2,
        'discount_percentage' => 12.00,
        'is_active' => true,
    ]);

    $cartKey = "{$product->id}:{$variation->id}";

    // Set 2 items in cart (which activates 12% discount)
    $response = $this->withSession(['cart' => [$cartKey => 2]])
        ->patchJson(route('cart.update', $product->slug), [
            'quantity' => 1,
            'cart_key' => $cartKey,
        ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'cartKey' => $cartKey,
            'quantity' => 1,
            'cartCount' => 1,
            'item' => [
                'key' => $cartKey,
                'quantity' => 1,
                'unitPrice' => '210.00',
                'lineTotal' => '210.00',
                'hasOffer' => false,
            ],
            'subtotal' => '210.00',
            'hasSavings' => false,
            'total' => '210.00',
            'isEmpty' => false,
        ]);

    // Remove item completely
    $destroyResponse = $this->deleteJson(route('cart.destroy', $product->slug), [
        'cart_key' => $cartKey,
    ]);

    $destroyResponse->assertOk()
        ->assertJson([
            'success' => true,
            'cartCount' => 0,
            'isEmpty' => true,
        ]);
});

test('storefront header/service banner displays UK delivery instead of worldwide', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('All over United Kingdom 🇬🇧');
    $response->assertDontSee('Free Shipping world wide');
});

test('about us page displays updated company details and team members', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee('KENKIE LTD UK based private registered company since 2024');
    $response->assertDontSee('since 2018');
    $response->assertSee('15579321');
    $response->assertDontSee('ICO Registered: 11147305');

    // Team member check for Faisal Zahid replacing Naveed Azhar
    $response->assertSee('FAISAL ZAHID');
    $response->assertSee('Sales Manager');
    $response->assertSee('faisal@kenkie.com');
    $response->assertDontSee('NAVEED AZHAR');
});

test('payments page is distinct and correctly linked in footers', function () {
    $response = $this->get(route('payments'));

    $response->assertOk();
    $response->assertSee('Payment Methods &amp; Security', false);
    $response->assertSee('Stripe');
    $response->assertSee('PayPal');

    // Verify footer links to payments route, not about us
    $home = $this->get(route('home'));
    $home->assertOk();
    $home->assertSee(route('payments'));
});

test('instagram links are updated to official kenkie profile', function () {
    $contact = $this->get(route('contact.us'));
    $contact->assertOk();
    $contact->assertSee('https://www.instagram.com/kenkieofficial');

    $home = $this->get(route('home'));
    $home->assertOk();
    $home->assertSee('https://www.instagram.com/kenkieofficial');
});

test('admin dashboard displays metrics and chart in GBP £', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('TOTAL REVENUE');
    $response->assertSee('£');
    $response->assertSee('AVG ORDER VALUE (AOV)');
    $response->assertSee('Revenue (£)');
    $response->assertDontSee('Revenue ($)');
});

test('admin can edit shipping cost and cart calculates shipping dynamically', function () {
    $admin = User::factory()->admin()->create();

    // Update shipping rates via admin profile
    $updateResponse = $this->actingAs($admin)->put(route('admin.profile.shipping'), [
        'standard_shipping_fee' => '12.50',
        'free_shipping_enabled' => true,
        'free_shipping_threshold' => '60.00',
        'estimated_delivery_text' => '1-2 Days Fast Tracked',
    ]);

    $updateResponse->assertRedirect(route('admin.profile.edit'));
    $updateResponse->assertSessionHas('status');

    $shipping = ShippingSetting::getSettings();
    expect((float) $shipping->standard_shipping_fee)->toBe(12.50);
    expect((float) $shipping->free_shipping_threshold)->toBe(60.00);

    // Below threshold (£40 spend) => £12.50 shipping fee
    $cart = new StorefrontCart;
    expect($cart->shippingFeeCents(4000))->toBe(1250);

    // At or above threshold (£60 spend) => FREE (£0)
    expect($cart->shippingFeeCents(6000))->toBe(0);
});

test('promotional popup discount code applies and deducts price on cart and checkout', function () {
    $popup = PromotionalPopup::create([
        'title' => 'Spring Clearance 20% OFF',
        'subtitle' => 'Save on your basket',
        'discount_code' => 'SPRING20',
        'discount_type' => 'percentage',
        'discount_value' => 20.00,
        'min_order_amount' => 25.00,
        'button_text' => 'Shop Now',
        'button_url' => '/shop-category',
        'is_active' => true,
    ]);

    $product = Product::factory()->create([
        'name' => 'Garden Shovel',
        'price' => 50.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    // Add product to cart
    $this->post(route('cart.store', $product->slug), ['quantity' => 1]);

    $customer = User::factory()->create();

    // Apply coupon
    $couponResponse = $this->withSession(['cart' => [(string) $product->id => 1]])
        ->post(route('cart.coupon.apply'), [
            'code' => 'SPRING20',
        ]);

    $couponResponse->assertSessionHas('applied_coupon');
    $appliedCoupon = session('applied_coupon');
    expect($appliedCoupon['code'])->toBe('SPRING20');

    // Checkout page reflects coupon discount
    $checkout = $this->actingAs($customer)
        ->withSession([
            'cart' => [(string) $product->id => 1],
            'applied_coupon' => $appliedCoupon,
        ])
        ->get(route('checkout.index'));
    $checkout->assertOk();
    $checkout->assertSee('SPRING20');
    $checkout->assertSee('-£10.00');

    // Post checkout with coupon
    $checkoutSubmit = $this->actingAs($customer)
        ->withSession([
            'cart' => [(string) $product->id => 1],
            'applied_coupon' => $appliedCoupon,
        ])
        ->post(route('checkout.store'), [
            'customer_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '07987654321',
            'address_line' => '44 Oxford Street',
            'city' => 'London',
            'region' => 'Greater London',
            'postal_code' => 'W1D 1BS',
            'country' => 'United Kingdom',
            'payment_method' => 'cash_on_delivery',
        ]);

    $checkoutSubmit->assertSessionHasNoErrors();
    $order = Order::latest()->first();
    expect($order)->not->toBeNull();
    expect($order->coupon_code)->toBe('SPRING20');
    expect((float) $order->discount_amount)->toBe(10.00);

    // Order success / confirmation page displays coupon discount
    $orderConfirmation = $this->get(route('orders.confirmation', $order->uuid));
    $orderConfirmation->assertOk();
    $orderConfirmation->assertSee('Coupon Discount (SPRING20)');
    $orderConfirmation->assertSee('-£10.00');

    // Admin order show page displays coupon discount
    $admin = User::factory()->admin()->create();
    $adminOrder = $this->actingAs($admin)->get(route('admin.orders.show', $order));
    $adminOrder->assertOk();
    $adminOrder->assertSee('Coupon Discount (SPRING20):');
    $adminOrder->assertSee('-£10.00');
});

test('admin can set product postage and courier options which appear on storefront and cart', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Heavy Duty Garden Spade',
        'price' => 32.00,
        'stock' => 15,
        'shipping_type' => 'custom',
        'shipping_fee' => 2.95,
        'courier_name' => 'inpost',
        'delivery_time_text' => '2-3 Working Days Tracked',
        'is_active' => true,
    ]);

    // Storefront product page renders the eBay-style postage & courier box
    $response = $this->get(route('products.show', $product->slug));
    $response->assertOk();
    $response->assertSee('Postage &amp; Delivery', false);
    $response->assertSee('£2.95');
    $response->assertSee('InPost');
    $response->assertSee('2-3 Working Days Tracked');
    $response->assertSee('Postage Details');

    // Cart shipping fee calculates custom product fee
    $cartFee = StorefrontCart::calculateShippingFeeCents(3200, [
        ['product' => $product, 'quantity' => 1],
    ]);
    expect($cartFee)->toBe(295);

    // Free shipping product
    $freeProduct = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Lightweight Pruning Shears',
        'price' => 18.00,
        'stock' => 20,
        'shipping_type' => 'free',
        'courier_name' => 'royal_mail',
        'delivery_time_text' => '1-2 Days Tracked 24',
        'is_active' => true,
    ]);

    $freeResponse = $this->get(route('products.show', $freeProduct->slug));
    $freeResponse->assertOk();
    $freeResponse->assertSee('Free Delivery');
    $freeResponse->assertSee('Royal Mail');

    $freeCartFee = StorefrontCart::calculateShippingFeeCents(1800, [
        ['product' => $freeProduct, 'quantity' => 1],
    ]);
    expect($freeCartFee)->toBe(0);
});

test('google oauth callback seamlessly authenticates existing registered user without redundant otp', function () {
    $existingUser = User::factory()->create([
        'name' => 'Alex Smith',
        'email' => 'alex@example.com',
        'password' => bcrypt('secret123'),
        'email_verified_at' => null,
    ]);

    config([
        'services.google.client_id' => 'dummy-client-id',
        'services.google.client_secret' => 'dummy-client-secret',
        'services.google.redirect' => 'https://kenkie.com/auth/google/callback',
    ]);

    Http::fake([
        'https://oauth2.googleapis.com/token' => Http::response([
            'access_token' => 'dummy-access-token',
        ]),
        'https://openidconnect.googleapis.com/v1/userinfo' => Http::response([
            'sub' => 'google-user-id-12345',
            'email' => 'alex@example.com',
            'email_verified' => true,
            'name' => 'Alex Smith',
        ]),
    ]);

    $response = $this->withSession([
        'google_oauth_state' => 'test-state-string',
        'google_oauth_code_verifier' => 'test-code-verifier',
    ])->get(route('google.callback', [
        'state' => 'test-state-string',
        'code' => 'test-auth-code',
    ]));

    $response->assertRedirect(route('home', absolute: false));
    $this->assertAuthenticatedAs($existingUser);

    $existingUser->refresh();
    expect($existingUser->email_verified_at)->not->toBeNull();
    expect($existingUser->login_otp_code)->toBeNull();
});

test('checkout respects product-wise custom postage fee instead of global default rate', function () {
    // Set global standard shipping to £8.95
    ShippingSetting::query()->updateOrCreate(['id' => 1], [
        'standard_shipping_fee' => 8.95,
        'free_shipping_enabled' => true,
        'free_shipping_threshold' => 50.00,
    ]);

    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Test Product Abdullah',
        'price' => 40.00,
        'stock' => 10,
        'shipping_type' => 'custom',
        'shipping_fee' => 5.00,
        'courier_name' => 'dpd',
        'delivery_time_text' => 'Delivered in 2-3 working days',
        'is_active' => true,
    ]);

    $customer = User::factory()->create();

    // Visit checkout page with this item in cart
    $checkout = $this->actingAs($customer)
        ->withSession(['cart' => [(string) $product->id => 1]])
        ->get(route('checkout.index'));

    $checkout->assertOk();
    $checkout->assertSee('£40.00'); // Subtotal
    $checkout->assertSee('£5.00');  // Custom Product Shipping fee
    $checkout->assertDontSee('£8.95'); // Must not use default £8.95 fee
    $checkout->assertSee('£45.00'); // Total (40 + 5)

    // Complete order checkout
    $orderResponse = $this->actingAs($customer)
        ->withSession(['cart' => [(string) $product->id => 1]])
        ->post(route('checkout.store'), [
            'customer_name' => 'Muhammad Abdullah',
            'email' => 'abdullah@example.com',
            'phone' => '+447123456789',
            'address_line' => '12 High Street',
            'city' => 'London',
            'region' => 'Greater London',
            'postal_code' => 'SW1A 1AA',
            'country' => 'United Kingdom',
            'payment_method' => 'cash_on_delivery',
        ]);

    $orderResponse->assertSessionHasNoErrors();
    $order = Order::latest()->first();
    expect($order)->not->toBeNull();
    expect((float) $order->shipping_fee)->toBe(5.00);
    expect((float) $order->total)->toBe(45.00);
});
