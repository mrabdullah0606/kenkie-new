<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'standard_shipping_fee',
    'free_shipping_enabled',
    'free_shipping_threshold',
    'estimated_delivery_text',
])]
class ShippingSetting extends Model
{
    /**
     * Retrieve the singleton shipping settings.
     */
    public static function getSettings(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'standard_shipping_fee' => 8.95,
                'free_shipping_enabled' => true,
                'free_shipping_threshold' => 50.00,
                'estimated_delivery_text' => '2-3 Business Days via Evri / Royal Mail',
            ]
        );
    }

    /**
     * Calculate shipping fee in cents based on order subtotal in cents.
     */
    public function calculateShippingCents(int $subtotalCents): int
    {
        if ($this->free_shipping_enabled && $subtotalCents >= (int) round($this->free_shipping_threshold * 100)) {
            return 0;
        }

        return max(0, (int) round($this->standard_shipping_fee * 100));
    }

    /**
     * Free shipping threshold in cents.
     */
    public function freeShippingThresholdCents(): int
    {
        return (int) round($this->free_shipping_threshold * 100);
    }

    /**
     * Formatted standard fee string with currency symbol.
     */
    public function getFormattedFeeAttribute(): string
    {
        return '£'.number_format((float) $this->standard_shipping_fee, 2);
    }

    /**
     * Formatted threshold string with currency symbol.
     */
    public function getFormattedThresholdAttribute(): string
    {
        return '£'.number_format((float) $this->free_shipping_threshold, 2);
    }

    protected function casts(): array
    {
        return [
            'standard_shipping_fee' => 'decimal:2',
            'free_shipping_enabled' => 'boolean',
            'free_shipping_threshold' => 'decimal:2',
        ];
    }
}
