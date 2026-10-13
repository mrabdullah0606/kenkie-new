<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('Google sign in creates a verified customer and continues through login otp', function () {
    config([
        'services.google.client_id' => 'google-client-id',
        'services.google.client_secret' => 'google-client-secret',
        'services.google.redirect' => 'http://localhost/auth/google/callback',
    ]);
    Mail::fake();
    Http::fake([
        'https://oauth2.googleapis.com/token' => Http::response(['access_token' => 'google-access-token']),
        'https://openidconnect.googleapis.com/v1/userinfo' => Http::response([
            'sub' => 'google-user-123',
            'email' => 'new.customer@example.com',
            'email_verified' => true,
            'name' => 'New Customer',
        ]),
    ]);

    $response = $this->withSession([
        'google_oauth_state' => 'valid-state',
        'google_oauth_code_verifier' => 'valid-verifier',
    ])->get(route('google.callback', [
        'code' => 'authorization-code',
        'state' => 'valid-state',
    ], absolute: false));

    $user = User::where('email', 'new.customer@example.com')->firstOrFail();

    $response->assertRedirect(route('login.otp', absolute: false));
    $response->assertSessionHas('login_2fa_user_id', $user->id);
    $this->assertGuest();
    expect($user->email_verified_at)->not->toBeNull()
        ->and($user->role)->toBe('customer')
        ->and($user->login_otp_code)->not->toBeNull();
});

test('Google sign in rejects an invalid oauth state', function () {
    Http::fake();

    $response = $this->withSession([
        'google_oauth_state' => 'expected-state',
        'google_oauth_code_verifier' => 'valid-verifier',
    ])->get(route('google.callback', [
        'code' => 'authorization-code',
        'state' => 'different-state',
    ], absolute: false));

    $response->assertRedirect(route('login', absolute: false));
    $response->assertSessionHasErrors('google');
    Http::assertNothingSent();
});

test('login requires a valid turnstile response when captcha is configured', function () {
    config([
        'services.turnstile.enabled' => true,
        'services.turnstile.secret_key' => 'turnstile-secret',
    ]);
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'hostname' => 'localhost',
        ]),
    ]);

    $response = $this->post('/login', [
        'email' => 'customer@example.com',
        'password' => 'password',
        'cf-turnstile-response' => 'invalid-token',
    ]);

    $response->assertSessionHasErrors('cf-turnstile-response');
    $this->assertGuest();
});

test('valid turnstile response allows login to continue to otp verification', function () {
    $user = User::factory()->customer()->create();
    config([
        'services.turnstile.enabled' => true,
        'services.turnstile.secret_key' => 'turnstile-secret',
    ]);
    Mail::fake();
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'hostname' => parse_url((string) config('app.url'), PHP_URL_HOST),
        ]),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'cf-turnstile-response' => 'valid-token',
    ]);

    $response->assertRedirect(route('login.otp', absolute: false));
    $response->assertSessionHas('login_2fa_user_id', $user->id);
});

test('registration requires a valid turnstile response when captcha is configured', function () {
    config([
        'services.turnstile.enabled' => true,
        'services.turnstile.secret_key' => 'turnstile-secret',
    ]);
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'hostname' => 'localhost',
        ]),
    ]);

    $response = $this->post('/register', [
        'name' => 'New Customer',
        'email' => 'new.customer@example.com',
        'password' => 'Strong-password-123',
        'password_confirmation' => 'Strong-password-123',
        'cf-turnstile-response' => 'invalid-token',
    ]);

    $response->assertSessionHasErrors('cf-turnstile-response');
    $this->assertDatabaseMissing('users', ['email' => 'new.customer@example.com']);
});

test('login and registration show google and turnstile options when configured', function () {
    config([
        'services.google.client_id' => 'google-client-id',
        'services.google.client_secret' => 'google-client-secret',
        'services.turnstile.enabled' => true,
        'services.turnstile.site_key' => 'turnstile-site-key',
    ]);

    $this->get('/login')
        ->assertOk()
        ->assertSee('Continue with Google')
        ->assertSee('data-sitekey="turnstile-site-key"', false);

    $this->get('/register')
        ->assertOk()
        ->assertSee('Continue with Google')
        ->assertSee('data-sitekey="turnstile-site-key"', false);
});

test('admin users receive otp and are redirected to admin dashboard upon verifying', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('login.otp', absolute: false));

    $admin->refresh();
    expect($admin->login_otp_code)->not->toBeNull();

    $verifyResponse = $this->withSession(['login_2fa_user_id' => $admin->id])
        ->post(route('login.verify-otp'), [
            'otp_code' => $admin->login_otp_code,
        ]);

    $this->assertAuthenticatedAs($admin);
    $verifyResponse->assertRedirect(route('admin.dashboard', absolute: false));
});

test('customer users receive otp and are redirected to home upon verifying', function () {
    $customer = User::factory()->customer()->create();

    $response = $this->post('/login', [
        'email' => $customer->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('login.otp', absolute: false));

    $customer->refresh();
    expect($customer->login_otp_code)->not->toBeNull();

    $verifyResponse = $this->withSession(['login_2fa_user_id' => $customer->id])
        ->post(route('login.verify-otp'), [
            'otp_code' => $customer->login_otp_code,
        ]);

    $this->assertAuthenticatedAs($customer);
    $verifyResponse->assertRedirect(route('home', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can not authenticate with invalid login otp', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $user->refresh();

    $this->withSession(['login_2fa_user_id' => $user->id])
        ->post(route('login.verify-otp'), [
            'otp_code' => '999999',
        ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
