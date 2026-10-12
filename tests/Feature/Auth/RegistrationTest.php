<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register and verify email with otp', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('register.otp', absolute: false));
    $this->assertGuest();

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->email_otp_code)->not->toBeNull();

    // Verify OTP code
    $otpResponse = $this->withSession(['register_user_id' => $user->id])
        ->post(route('register.verify-otp'), [
            'otp_code' => $user->email_otp_code,
        ]);

    $this->assertAuthenticatedAs($user);
    $otpResponse->assertRedirect(route('home', absolute: false));
});
