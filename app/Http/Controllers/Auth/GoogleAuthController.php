<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $clientId = config('services.google.client_id');
        $redirectUri = $this->getRedirectUri();

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
            return $this->failedSignIn('Invalid OAuth state or authorization code mismatch.');
        }

        try {
            $tokenResponse = Http::asForm()->timeout(10)->post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'code' => $code,
                'code_verifier' => $verifier,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->getRedirectUri(),
            ]);

            $accessToken = $tokenResponse->json('access_token');

            if (! $tokenResponse->successful() || ! is_string($accessToken) || $accessToken === '') {
                Log::warning('Google OAuth token exchange failed', [
                    'status' => $tokenResponse->status(),
                    'body' => $tokenResponse->json() ?? $tokenResponse->body(),
                ]);

                return $this->failedSignIn('Unable to exchange authorization code with Google.');
            }

            $profileResponse = Http::withToken($accessToken)
                ->timeout(10)
                ->get('https://openidconnect.googleapis.com/v1/userinfo');
        } catch (\Throwable $e) {
            Log::error('Google OAuth API communication error: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return $this->failedSignIn('Failed to connect to Google authentication service.');
        }

        $profile = $profileResponse->json();
        $email = is_array($profile) ? $profile['email'] ?? null : null;

        if (! $profileResponse->successful()
            || ! is_array($profile)
            || ! is_string($profile['sub'] ?? null)
            || ! is_string($email)
            || $email === ''
            || ($profile['email_verified'] ?? false) !== true) {
            Log::warning('Google OAuth profile verification failed', [
                'status' => $profileResponse->status(),
                'profile' => $profile,
            ]);

            return $this->failedSignIn('Google account profile or email verification is incomplete.');
        }

        $email = Str::lower(trim($email));
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
        } else {
            // Existing user (registered normally with email/password or previously)
            // Google OAuth proves email ownership: ensure account is verified & clear pending OTPs
            if ($user->email_verified_at === null) {
                $user->email_verified_at = now();
            }
            $user->email_otp_code = null;
            $user->email_otp_expires_at = null;
            $user->login_otp_code = null;
            $user->login_otp_expires_at = null;
            $user->save();
        }

        // Direct authenticated session without redundant OTP challenge
        $request->session()->regenerate();
        Auth::login($user, true);

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('home', absolute: false));
    }

    private function failedSignIn(?string $reason = null): RedirectResponse
    {
        if ($reason) {
            Log::info('Google sign-in attempt rejected: '.$reason);
        }

        return redirect()->route('login')->withErrors([
            'google' => 'Google sign-in could not be completed. Please try again.',
        ]);
    }

    private function getRedirectUri(): ?string
    {
        $redirectUri = config('services.google.redirect');

        if (! is_string($redirectUri) || blank($redirectUri)) {
            return null;
        }

        return preg_replace('#(?<!:)//+#', '/', trim($redirectUri));
    }
}
