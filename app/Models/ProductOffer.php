<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id',
    'title',
    'min_quantity',
    'discount_percentage',
    'badge_label',
    'is_active',
    'allow_on_discounted',
    'starts_at',
    'ends_at',
])]
class ProductOffer extends Model
{
    use HasFactory;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('min_quantity');
    }

    public function discountedPriceFor(float $basePrice): float
    {
        $discountFactor = (100 - (float) $this->discount_percentage) / 100;

        return round($basePrice * $discountFactor, 2);
    }

    protected function casts(): array
    {
        return [
            'min_quantity' => 'integer',
            'discount_percentage' => 'decimal:2',
            'is_active' => 'boolean',
            'allow_on_discounted' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }
}
