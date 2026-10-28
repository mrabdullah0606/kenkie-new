<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Throwable;

#[Fillable([
    'stripe_enabled',
    'stripe_mode',
    'stripe_key',
    'stripe_secret',
    'stripe_webhook_secret',
    'cod_enabled',
    'cod_instructions',
    'paypal_enabled',
    'paypal_mode',
    'paypal_client_id',
    'paypal_secret',
])]
class PaymentSetting extends Model
{
    /**
     * Attributes hidden from array / JSON serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'stripe_secret',
        'stripe_webhook_secret',
        'paypal_secret',
    ];

    /**
     * Retrieve the singleton settings instance.
     */
    public static function getSettings(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'stripe_enabled' => true,
                'stripe_mode' => 'test',
                'stripe_key' => config('services.stripe.key'),
                'stripe_secret' => config('services.stripe.secret'),
                'stripe_webhook_secret' => config('services.stripe.webhook_secret'),
                'cod_enabled' => app()->environment('testing'),
                'cod_instructions' => 'Pay cash directly upon delivery of your order at your doorstep.',
                'paypal_enabled' => true,
                'paypal_mode' => 'sandbox',
            ]
        );
    }

    /**
     * Get the active Stripe Publishable Key (DB priority, config fallback).
     */
    public static function getStripeKey(): ?string
    {
        $settings = static::getSettings();

        return ! empty($settings->stripe_key)
            ? $settings->stripe_key
            : config('services.stripe.key');
    }

    /**
     * Get the active Stripe Secret Key (DB priority, config fallback).
     */
    public static function getStripeSecret(): ?string
    {
        $settings = static::getSettings();

        try {
            $secret = $settings->stripe_secret;
        } catch (Throwable) {
            $secret = null;
        }

        return ! empty($secret)
            ? $secret
            : config('services.stripe.secret');
    }

    /**
     * Get the active Stripe Webhook Secret (DB priority, config fallback).
     */
    public static function getStripeWebhookSecret(): ?string
    {
        $settings = static::getSettings();

        try {
            $webhook = $settings->stripe_webhook_secret;
        } catch (Throwable) {
            $webhook = null;
        }

        return ! empty($webhook)
            ? $webhook
            : config('services.stripe.webhook_secret');
    }

    /**
     * Determine if Stripe is fully configured with both public and secret keys.
     */
    public static function isStripeConfigured(): bool
    {
        $key = static::getStripeKey();
        $secret = static::getStripeSecret();

        return ! empty($key) && ! empty($secret);
    }

    /**
     * Get masked representation of Stripe secret for display in admin.
     */
    public function getMaskedStripeSecretAttribute(): ?string
    {
        try {
            $secret = $this->stripe_secret;
        } catch (Throwable) {
            $secret = null;
        }

        if (empty($secret)) {
            $fallback = config('services.stripe.secret');
            if (empty($fallback)) {
                return null;
            }
            $secret = $fallback;
        }

        if (strlen($secret) <= 12) {
            return '••••••••';
        }

        $prefix = substr($secret, 0, 7);
        $suffix = substr($secret, -4);

        return "{$prefix}••••••••{$suffix}";
    }

    /**
     * Get masked representation of Stripe webhook secret for display in admin.
     */
    public function getMaskedWebhookSecretAttribute(): ?string
    {
        try {
            $secret = $this->stripe_webhook_secret;
        } catch (Throwable) {
            $secret = null;
        }

        if (empty($secret)) {
            $fallback = config('services.stripe.webhook_secret');
            if (empty($fallback)) {
                return null;
            }
            $secret = $fallback;
        }

        if (strlen($secret) <= 10) {
            return '••••••••';
        }

        $prefix = substr($secret, 0, 6);
        $suffix = substr($secret, -4);

        return "{$prefix}••••••••{$suffix}";
    }

    /**
     * Get the active PayPal Client ID (DB priority, config fallback).
     */
    public static function getPayPalClientId(): ?string
    {
        $settings = static::getSettings();

        return ! empty($settings->paypal_client_id)
            ? $settings->paypal_client_id
            : config('services.paypal.client_id');
    }

    /**
     * Get the active PayPal Secret (DB priority, config fallback).
     */
    public static function getPayPalSecret(): ?string
    {
        $settings = static::getSettings();

        try {
            $secret = $settings->paypal_secret;
        } catch (Throwable) {
            $secret = null;
        }

        return ! empty($secret)
            ? $secret
            : config('services.paypal.secret');
    }

    /**
     * Determine if PayPal is configured with credentials.
     */
    public static function isPayPalConfigured(): bool
    {
        return ! empty(static::getPayPalClientId()) && ! empty(static::getPayPalSecret());
    }

    /**
     * Get masked representation of PayPal secret for display in admin.
     */
    public function getMaskedPayPalSecretAttribute(): ?string
    {
        try {
            $secret = $this->paypal_secret;
        } catch (Throwable) {
            $secret = null;
        }

        if (empty($secret)) {
            $fallback = config('services.paypal.secret');
            if (empty($fallback)) {
                return null;
            }
            $secret = $fallback;
        }

        if (strlen($secret) <= 12) {
            return '••••••••';
        }

        $prefix = substr($secret, 0, 6);
        $suffix = substr($secret, -4);

        return "{$prefix}••••••••{$suffix}";
    }

    /**
     * Attribute casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stripe_enabled' => 'boolean',
            'cod_enabled' => 'boolean',
            'paypal_enabled' => 'boolean',
            'stripe_secret' => 'encrypted',
            'stripe_webhook_secret' => 'encrypted',
            'paypal_secret' => 'encrypted',
        ];
    }
}
