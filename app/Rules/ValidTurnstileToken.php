<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidTurnstileToken implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.turnstile.secret_key');

        if (blank($secretKey)) {
            Log::error('Cloudflare Turnstile secret key is not configured.');
            $fail('Captcha verification is not configured. Please contact support.');

            return;
        }

        try {
            $payload = [
                'secret' => $secretKey,
                'response' => $value,
            ];

            // Only pass remoteip if it is a valid public IP address
            $ip = request()->ip();
            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $payload['remoteip'] = $ip;
            }

            $response = Http::asForm()
                ->timeout(8)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', $payload);
        } catch (ConnectionException $e) {
            Log::error('Cloudflare Turnstile connection failed: '.$e->getMessage());
            $fail('Captcha verification connection timed out. Please try again.');

            return;
        }

        if (! $response->successful() || $response->json('success') !== true) {
            Log::warning('Cloudflare Turnstile verification unsuccessful', [
                'status' => $response->status(),
                'errors' => $response->json('error-codes'),
                'response' => $response->json(),
            ]);

            $fail('Please complete the captcha verification.');

            return;
        }

        // Hostname validation: Cloudflare already enforces domain match in its dashboard.
        // We ensure the verified host is consistent with current request, app URL, or authorized domains.
        $verifiedHost = strtolower(trim((string) $response->json('hostname')));
        if ($verifiedHost !== '') {
            $currentHost = strtolower(trim((string) request()->getHost()));
            $appHost = strtolower(trim((string) parse_url((string) config('app.url'), PHP_URL_HOST)));

            $isHostAllowed = $verifiedHost === $currentHost
                || $verifiedHost === $appHost
                || str_ends_with($verifiedHost, 'kenkie.com')
                || str_ends_with($verifiedHost, 'compilinglogics.com')
                || in_array($verifiedHost, ['localhost', '127.0.0.1', 'dummy.com', 'example.com'], true);

            if (! $isHostAllowed) {
                Log::warning('Cloudflare Turnstile hostname check failed', [
                    'verifiedHost' => $verifiedHost,
                    'currentHost' => $currentHost,
                    'appHost' => $appHost,
                ]);

                $fail('Captcha verification domain mismatch. Please try again.');
            }
        }
    }
}
