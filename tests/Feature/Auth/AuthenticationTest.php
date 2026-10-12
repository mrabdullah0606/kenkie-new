<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
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
