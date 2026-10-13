<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect');

        if (blank($clientId) || blank(config('services.google.client_secret')) || blank($redirectUri)) {
            return redirect()->route('login')->withErrors([
                'google' => 'Google sign-in is not configured yet.',
            ]);
        }

        $state = Str::random(40);
        $verifier = Str::random(64);
        request()->session()->put([
            'google_oauth_state' => $state,
            'google_oauth_code_verifier' => $verifier,
        ]);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'code_challenge' => rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '='),
            'code_challenge_method' => 'S256',
            'prompt' => 'select_account',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function callback(Request $request): RedirectResponse
    {
        $expectedState = $request->session()->pull('google_oauth_state');
        $verifier = $request->session()->pull('google_oauth_code_verifier');
        $state = $request->query('state');
        $code = $request->query('code');

        if (! is_string($expectedState)
            || ! is_string($state)
            || ! hash_equals($expectedState, $state)
            || ! is_string($verifier)
            || ! is_string($code)
            || $code === '') {
            return $this->failedSignIn();
        }

        try {
            $tokenResponse = Http::asForm()->timeout(10)->post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'code' => $code,
                'code_verifier' => $verifier,
                'grant_type' => 'authorization_code',
                'redirect_uri' => config('services.google.redirect'),
            ]);

            $accessToken = $tokenResponse->json('access_token');

            if (! $tokenResponse->successful() || ! is_string($accessToken) || $accessToken === '') {
                return $this->failedSignIn();
            }

            $profileResponse = Http::withToken($accessToken)
                ->timeout(10)
                ->get('https://openidconnect.googleapis.com/v1/userinfo');
        } catch (ConnectionException) {
            return $this->failedSignIn();
        }

        $profile = $profileResponse->json();
        $email = is_array($profile) ? $profile['email'] ?? null : null;

        if (! $profileResponse->successful()
            || ! is_array($profile)
            || ! is_string($profile['sub'] ?? null)
            || ! is_string($email)
            || $email === ''
            || ($profile['email_verified'] ?? false) !== true) {
            return $this->failedSignIn();
        }

        $email = Str::lower($email);
        $user = User::where('email', $email)->first();

        if (! $user) {
            $name = $profile['name'] ?? Str::before($email, '@');

            $user = User::create([
                'name' => is_string($name) ? Str::limit(trim($name), 255, '') : Str::before($email, '@'),
                'email' => $email,
                'password' => Str::random(64),
                'role' => 'customer',
            ]);
            $user->forceFill(['email_verified_at' => now()])->save();
        } elseif ($user->email_verified_at === null) {
            $user->forceFill([
                'email_verified_at' => now(),
                'email_otp_code' => null,
                'email_otp_expires_at' => null,
            ])->save();
        }

        $otpCode = (string) random_int(100000, 999999);
        $user->login_otp_code = $otpCode;
        $user->login_otp_expires_at = now()->addMinutes(10);
        $user->save();

        OtpVerificationController::sendOtpEmail($user, $otpCode, 'login');

        $request->session()->regenerate();
        $request->session()->put([
            'login_2fa_user_id' => $user->id,
            'login_2fa_remember' => false,
        ]);

        return redirect()->route('login.otp');
    }

    private function failedSignIn(): RedirectResponse
    {
        return redirect()->route('login')->withErrors([
            'google' => 'Google sign-in could not be completed. Please try again.',
        ]);
    }
}
