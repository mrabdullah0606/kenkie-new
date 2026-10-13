<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AuthOtpMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Show registration OTP verification screen.
     */
    public function showRegisterOtp(Request $request): View|RedirectResponse
    {
        $userId = session('register_user_id');

        if (! $userId) {
            return redirect()->route('register');
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget('register_user_id');

            return redirect()->route('register');
        }

        return view('auth.verify-register-otp', [
            'email' => $user->email,
        ]);
    }

    /**
     * Verify registration OTP and activate user account.
     */
    public function verifyRegisterOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $userId = session('register_user_id');

        if (! $userId) {
            return redirect()->route('register');
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget('register_user_id');

            return redirect()->route('register');
        }

        $enteredCode = trim($request->otp_code);

        if (! $user->email_otp_code || $user->email_otp_code !== $enteredCode) {
            return back()->withErrors(['otp_code' => 'The verification code entered is incorrect.'])->withInput();
        }

        if (! $user->email_otp_expires_at || now()->gt($user->email_otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'The verification code has expired. Please click resend to get a new code.'])->withInput();
        }

        $user->email_verified_at = now();
        $user->email_otp_code = null;
        $user->email_otp_expires_at = null;
        $user->save();

        event(new Registered($user));

        Auth::login($user);

        session()->forget('register_user_id');

        return redirect()->route('home')->with('status', 'Your email has been verified! Welcome to Kenkie.');
    }

    /**
     * Resend registration OTP.
     */
    public function resendRegisterOtp(Request $request): RedirectResponse
    {
        $userId = session('register_user_id');

        if (! $userId) {
            return redirect()->route('register');
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget('register_user_id');

            return redirect()->route('register');
        }

        $throttleKey = 'resend-register-otp:'.$user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors(['otp_code' => "Please wait {$seconds} seconds before requesting another code."]);
        }

        RateLimiter::hit($throttleKey, 60);

        $code = (string) random_int(100000, 999999);
        $user->email_otp_code = $code;
        $user->email_otp_expires_at = now()->addMinutes(10);
        $user->save();

        self::sendOtpEmail($user, $code, 'registration');

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    /**
     * Send OTP email with logging and graceful fallback if SMTP credentials not yet provided.
     */
    public static function sendOtpEmail(User $user, string $code, string $type): void
    {
        Log::info("Generated {$type} OTP code for {$user->email}: {$code}");

        try {
            if (config('mail.default') === 'smtp' && empty(config('mail.mailers.smtp.username'))) {
                Log::info("SMTP credentials not set in .env yet; OTP code {$code} logged for {$user->email}");

                return;
            }

            Mail::to($user->email)->send(new AuthOtpMail($code, $type, $user->name));
        } catch (\Throwable $e) {
            Log::error("Failed sending {$type} OTP email to {$user->email}: {$e->getMessage()}");
        }
    }

    /**
     * Show login OTP verification screen.
     */
    public function showLoginOtp(Request $request): View|RedirectResponse
    {
        $userId = session('login_2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget(['login_2fa_user_id', 'login_2fa_remember']);

            return redirect()->route('login');
        }

        return view('auth.verify-login-otp', [
            'email' => $user->email,
        ]);
    }

    /**
     * Verify login OTP and authenticate user.
     */
    public function verifyLoginOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $userId = session('login_2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget(['login_2fa_user_id', 'login_2fa_remember']);

            return redirect()->route('login');
        }

        $enteredCode = trim($request->otp_code);

        if (! $user->login_otp_code || $user->login_otp_code !== $enteredCode) {
            return back()->withErrors(['otp_code' => 'The security code entered is incorrect.'])->withInput();
        }

        if (! $user->login_otp_expires_at || now()->gt($user->login_otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'The security code has expired. Please click resend to get a new code.'])->withInput();
        }

        $user->login_otp_code = null;
        $user->login_otp_expires_at = null;
        $user->save();

        $remember = (bool) session('login_2fa_remember', false);

        Auth::login($user, $remember);

        session()->forget(['login_2fa_user_id', 'login_2fa_remember']);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Resend login OTP.
     */
    public function resendLoginOtp(Request $request): RedirectResponse
    {
        $userId = session('login_2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget(['login_2fa_user_id', 'login_2fa_remember']);

            return redirect()->route('login');
        }

        $throttleKey = 'resend-login-otp:'.$user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors(['otp_code' => "Please wait {$seconds} seconds before requesting another code."]);
        }

        RateLimiter::hit($throttleKey, 60);

        $code = (string) random_int(100000, 999999);
        $user->login_otp_code = $code;
        $user->login_otp_expires_at = now()->addMinutes(10);
        $user->save();

        self::sendOtpEmail($user, $code, 'login');

        return back()->with('status', 'A new login security code has been sent to your email.');
    }
}
