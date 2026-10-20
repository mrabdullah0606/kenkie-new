<?php

use App\Mail\AdminNewOrderNotificationMail;
use App\Mail\AdminNewUserRegisteredMail;
use App\Mail\CustomerOrderConfirmationMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\FraudDetectionService;
use Illuminate\Support\Facades\Mail;

test('order placement sends notification to admin and confirmation to customer', function () {
    Mail::fake();

    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'price' => 25.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $user = User::factory()->customer()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 2]])
        ->post(route('checkout.store'), [
            'customer_name' => 'John Customer',
            'email' => 'customer@example.com',
            'phone' => '07123456789',
            'address_line' => '12 High Street',
            'city' => 'London',
            'postal_code' => 'SW1A 1AA',
            'country' => 'United Kingdom',
            'payment_method' => 'cash_on_delivery',
        ]);

    $order = Order::query()->where('email', 'customer@example.com')->first();
    expect($order)->not->toBeNull();
    $response->assertRedirect(route('orders.confirmation', $order->uuid));

    // Assert Admin email sent
    $adminEmail = config('mail.admin_address', 'info@kenkie.com');
    Mail::assertSent(AdminNewOrderNotificationMail::class, function ($mail) use ($adminEmail, $order) {
        return $mail->hasTo($adminEmail) && $mail->order->id === $order->id;
    });

    // Assert Customer email sent
    Mail::assertSent(CustomerOrderConfirmationMail::class, function ($mail) use ($order) {
        return $mail->hasTo('customer@example.com') && $mail->order->id === $order->id;
    });
});

test('fraud detection service identifies disposable emails and high order values', function () {
    $lineItems = [
        [
            'displayName' => 'Bulk Product',
            'quantity' => 12,
            'unitPriceCents' => 5000,
            'lineTotalCents' => 60000,
        ],
    ];

    $eval = FraudDetectionService::evaluate([
        'email' => 'scammer@mailinator.com',
        'customer_name' => 'test user',
        'phone' => '1111111',
    ], $lineItems, null, 600.00);

    expect($eval['score'])->toBe('high');
    expect($eval['numeric_score'])->toBeGreaterThanOrEqual(50);
    expect($eval['flags'])->toContain('Disposable / temporary email domain detected (@mailinator.com).');
});

test('new user verification dispatches admin registration notification', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
        'email_otp_code' => '123456',
        'email_otp_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession(['register_user_id' => $user->id])
        ->post(route('register.verify-otp'), [
            'otp_code' => '123456',
        ]);

    $response->assertRedirect(route('home'));

    $adminEmail = config('mail.admin_address', 'info@kenkie.com');
    Mail::assertSent(AdminNewUserRegisteredMail::class, function ($mail) use ($adminEmail, $user) {
        return $mail->hasTo($adminEmail) && $mail->user->id === $user->id;
    });
});

test('honeypot traps automated bot submissions on register', function () {
    $response = $this->post(route('register'), [
        'name' => 'Bot Tester',
        'email' => 'bot@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'hp_username' => 'spam_bot_input', // Honeypot filled by bot
    ]);

    // Bot is trapped and redirected to login without creating a user
    $response->assertRedirect(route('login'));
    expect(User::where('email', 'bot@example.com')->exists())->toBeFalse();
});
