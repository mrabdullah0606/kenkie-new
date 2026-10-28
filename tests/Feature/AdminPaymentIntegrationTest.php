<?php

use App\Models\Order;
use App\Models\PaymentSetting;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    PaymentSetting::query()->delete();
});

it('prevents unauthenticated guests and regular users from accessing payment settings', function () {
    $this->get(route('admin.payments.index'))
        ->assertRedirect(route('login'));

    $customer = User::factory()->customer()->create();
    $this->actingAs($customer)
        ->get(route('admin.payments.index'))
        ->assertForbidden();
});

it('allows an administrator to view payment settings page with current gateway details', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.payments.index'))
        ->assertOk()
        ->assertSee('Payment Gateways')
        ->assertSee('Stripe Payment Gateway')
        ->assertSee('Cash On Delivery (COD)')
        ->assertSee('PayPal Gateway');
});

it('allows an administrator to update Stripe gateway keys and environment mode in database', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)
        ->put(route('admin.payments.stripe'), [
            'stripe_enabled' => '1',
            'stripe_mode' => 'live',
            'stripe_key' => 'pk_live_custom_stripe_key_12345',
            'stripe_secret' => 'sk_live_custom_stripe_secret_67890',
            'stripe_webhook_secret' => 'whsec_custom_webhook_secret_abcde',
        ]);

    $response->assertRedirect(route('admin.payments.index'))
        ->assertSessionHas('status');

    $settings = PaymentSetting::getSettings();
    expect($settings->stripe_enabled)->toBeTrue();
    expect($settings->stripe_mode)->toBe('live');
    expect($settings->stripe_key)->toBe('pk_live_custom_stripe_key_12345');
    expect(PaymentSetting::getStripeKey())->toBe('pk_live_custom_stripe_key_12345');
    expect(PaymentSetting::getStripeSecret())->toBe('sk_live_custom_stripe_secret_67890');
    expect(PaymentSetting::getStripeWebhookSecret())->toBe('whsec_custom_webhook_secret_abcde');
});

it('preserves existing Stripe secret when administrator leaves secret field blank', function () {
    $admin = User::factory()->admin()->create();

    // First save
    $this->actingAs($admin)->put(route('admin.payments.stripe'), [
        'stripe_enabled' => '1',
        'stripe_mode' => 'test',
        'stripe_key' => 'pk_test_first_key',
        'stripe_secret' => 'sk_test_first_secret',
    ]);

    expect(PaymentSetting::getStripeSecret())->toBe('sk_test_first_secret');

    // Second save with blank secret field
    $this->actingAs($admin)->put(route('admin.payments.stripe'), [
        'stripe_enabled' => '1',
        'stripe_mode' => 'test',
        'stripe_key' => 'pk_test_updated_key',
        'stripe_secret' => '',
    ]);

    expect(PaymentSetting::getStripeKey())->toBe('pk_test_updated_key');
    expect(PaymentSetting::getStripeSecret())->toBe('sk_test_first_secret');
});

it('allows an administrator to update Cash On Delivery settings', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.payments.cod'), [
            'cod_enabled' => '1',
            'cod_instructions' => 'Please have exact cash ready for the driver.',
        ])
        ->assertRedirect(route('admin.payments.index'))
        ->assertSessionHas('status');

    $settings = PaymentSetting::getSettings();
    expect($settings->cod_enabled)->toBeTrue();
    expect($settings->cod_instructions)->toBe('Please have exact cash ready for the driver.');
});

it('allows an administrator to update PayPal settings and test connection', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.payments.paypal'), [
            'paypal_enabled' => '1',
            'paypal_mode' => 'sandbox',
            'paypal_client_id' => 'client_id_paypal_123',
            'paypal_secret' => 'client_secret_paypal_456',
        ])
        ->assertRedirect(route('admin.payments.index'))
        ->assertSessionHas('status');

    $settings = PaymentSetting::getSettings();
    expect($settings->paypal_enabled)->toBeTrue();
    expect($settings->paypal_mode)->toBe('sandbox');
    expect($settings->paypal_client_id)->toBe('client_id_paypal_123');

    // Test connection endpoint with placeholder/simulation
    $this->actingAs($admin)
        ->post(route('admin.payments.paypal.test'))
        ->assertRedirect(route('admin.payments.index'));
});

it('places an order using paypal payment method in simulation mode', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 20.0, 'stock' => 5]);

    $settings = PaymentSetting::getSettings();
    $settings->update([
        'paypal_enabled' => true,
        'paypal_client_id' => 'placeholder_client_id',
        'paypal_secret' => 'placeholder_secret',
    ]);

    $response = $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), [
            'customer_name' => 'PayPal Customer',
            'email' => 'paypal.customer@example.com',
            'phone' => '07123456789',
            'address_line' => '10 Downing Street',
            'city' => 'London',
            'postal_code' => 'SW1A 2AA',
            'country' => 'United Kingdom',
            'payment_method' => 'paypal',
        ]);

    $order = Order::query()->where('customer_name', 'PayPal Customer')->firstOrFail();
    expect($order->payment_method)->toBe('paypal');
    expect($order->payment_status)->toBe('paid');
    $response->assertRedirect(route('orders.confirmation', $order->uuid));
});

it('preserves cart and restores stock when user cancels paypal checkout', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10, 'price' => 25.0]);

    $order = Order::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_method' => 'paypal',
    ]);
    $order->items()->create([
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => 2,
        'unit_price' => 25.0,
        'line_total' => 50.0,
    ]);

    $product->decrement('stock', 2);
    expect($product->fresh()->stock)->toBe(8);

    $response = $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 2]])
        ->get(route('checkout.paypal.cancel', ['order' => $order->uuid]));

    $response->assertRedirect(route('checkout.index'));
    $response->assertSessionHas('error');
    $response->assertSessionHas('cart', [$product->id => 2]);

    $order->refresh();
    expect($order->status)->toBe('cancelled');
    expect($order->payment_status)->toBe('cancelled');
    expect($product->fresh()->stock)->toBe(10);
});

it('falls back to services config when database keys are null', function () {
    config([
        'services.stripe.key' => 'pk_config_fallback',
        'services.stripe.secret' => 'sk_config_fallback',
        'services.stripe.webhook_secret' => 'whsec_config_fallback',
    ]);

    PaymentSetting::query()->update([
        'stripe_key' => null,
        'stripe_secret' => null,
        'stripe_webhook_secret' => null,
    ]);

    expect(PaymentSetting::getStripeKey())->toBe('pk_config_fallback');
    expect(PaymentSetting::getStripeSecret())->toBe('sk_config_fallback');
    expect(PaymentSetting::getStripeWebhookSecret())->toBe('whsec_config_fallback');
});

it('respects enabled and disabled payment methods during checkout validation', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 10.0, 'stock' => 5]);

    // Disable Stripe and COD in DB, enable only PayPal
    $settings = PaymentSetting::getSettings();
    $settings->update([
        'stripe_enabled' => false,
        'paypal_enabled' => true,
        'cod_enabled' => false,
    ]);

    // Attempt Cash On Delivery should fail validation
    $response = $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), [
            'customer_name' => 'Jane Customer',
            'email' => 'jane@example.com',
            'phone' => '07123456789',
            'address_line' => '12 High Street',
            'city' => 'London',
            'postal_code' => 'SW1A 1AA',
            'country' => 'United Kingdom',
            'payment_method' => 'cash_on_delivery',
        ]);

    $response->assertSessionHasErrors('payment_method');

    // Attempt PayPal should succeed
    $validResponse = $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), [
            'customer_name' => 'Jane Customer',
            'email' => 'jane@example.com',
            'phone' => '07123456789',
            'address_line' => '12 High Street',
            'city' => 'London',
            'postal_code' => 'SW1A 1AA',
            'country' => 'United Kingdom',
            'payment_method' => 'paypal',
        ]);

    $validResponse->assertRedirect();
});

it('handles stripe webhook checkout.session.completed event and marks order as paid', function () {
    $order = Order::factory()->create([
        'payment_status' => 'pending',
        'status' => 'pending',
        'stripe_session_id' => 'cs_test_session_9999',
    ]);

    $payload = json_encode([
        'id' => 'evt_test_123',
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => 'cs_test_session_9999',
                'payment_status' => 'paid',
                'payment_intent' => 'pi_test_intent_8888',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_uuid' => $order->uuid,
                ],
            ],
        ],
    ]);

    $response = $this->postJson(route('stripe.webhook'), json_decode($payload, true));

    $response->assertOk()
        ->assertJson(['status' => 'success']);

    $order->refresh();
    expect($order->payment_status)->toBe('paid');
    expect($order->status)->toBe('processing');
    expect($order->stripe_payment_intent_id)->toBe('pi_test_intent_8888');
});

it('preserves cart and restores stock when user cancels stripe checkout', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10, 'price' => 15.0]);

    $order = Order::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);
    $order->items()->create([
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => 2,
        'unit_price' => 15.0,
        'line_total' => 30.0,
    ]);

    // Simulate stock was decremented during store()
    $product->decrement('stock', 2);
    expect($product->fresh()->stock)->toBe(8);

    // User visits cancel URL with their cart intact
    $response = $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 2]])
        ->get(route('checkout.stripe.cancel', ['order' => $order->uuid]));

    $response->assertRedirect(route('checkout.index'));
    $response->assertSessionHas('error');
    $response->assertSessionHas('cart', [$product->id => 2]);

    $order->refresh();
    expect($order->status)->toBe('cancelled');
    expect($order->payment_status)->toBe('cancelled');
    expect($product->fresh()->stock)->toBe(10);

    // Following redirect back to checkout.index displays prefilled details
    $checkoutPage = $this->actingAs($user)->get(route('checkout.index'));
    $checkoutPage->assertOk()
        ->assertSee($order->customer_name)
        ->assertSee($order->phone)
        ->assertSee($order->address_line)
        ->assertSee($order->city)
        ->assertSee($order->postal_code);
});

it('rejects checkout orders outside the United Kingdom', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 20.0, 'stock' => 5]);

    $this->actingAs($user)->post(route('cart.store', $product->slug), ['quantity' => 1]);

    $response = $this->actingAs($user)->post(route('checkout.store'), [
        'customer_name' => 'Foreign Buyer',
        'email' => 'foreign@example.com',
        'phone' => '+1 555 123 4567',
        'address_line' => '123 Main Street',
        'city' => 'New York',
        'region' => 'NY',
        'postal_code' => '10001',
        'country' => 'United States',
        'payment_method' => 'stripe',
    ]);

    $response->assertSessionHasErrors(['country']);
    expect(Order::count())->toBe(0);
});

it('renders environment mode badges with high visibility styling in admin payment dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.payments.index'));

    $response->assertOk()
        ->assertSee('TEST MODE')
        ->assertSee('SANDBOX MODE');
});
