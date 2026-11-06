<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'subtitle',
    'content',
    'discount_code',
    'discount_type',
    'discount_value',
    'min_order_amount',
    'button_text',
    'button_url',
    'image',
    'target_page',
    'delay_seconds',
    'is_active',
])]
class PromotionalPopup extends Model
{
    use HasFactory;

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where(function ($q) use ($page) {
            $q->where('target_page', 'all')
                ->orWhere('target_page', $page);
        });
    }

    /**
     * Calculate discount in pence/cents given a subtotal in pence/cents.
     */
    public function calculateDiscountCents(int $subtotalCents): int
    {
        $minSpendCents = (int) round(((float) ($this->min_order_amount ?? 0)) * 100);
        if ($minSpendCents > 0 && $subtotalCents < $minSpendCents) {
            return 0;
        }

        $value = (float) ($this->discount_value ?? 0);
        if ($value <= 0) {
            return 0;
        }

        if (($this->discount_type ?? 'percentage') === 'fixed') {
            $fixedCents = (int) round($value * 100);

            return min($subtotalCents, $fixedCents);
        }

        // Percentage
        return min($subtotalCents, (int) round($subtotalCents * ($value / 100)));
    }

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'delay_seconds' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
