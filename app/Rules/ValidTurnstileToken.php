<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
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
        if (blank(config('services.turnstile.secret_key'))) {
            $fail('Captcha verification is not configured. Please contact support.');

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
        } catch (ConnectionException) {
            $fail('Captcha verification failed. Please try again.');

            return;
        }

        $expectedHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $verifiedHost = $response->json('hostname');

        if (! $response->successful()
            || $response->json('success') !== true
            || ! is_string($expectedHost)
            || ! is_string($verifiedHost)
            || ! hash_equals(strtolower($expectedHost), strtolower($verifiedHost))) {
            $fail('Please complete the captcha verification.');
        }
    }
}
