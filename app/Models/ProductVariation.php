<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id',
    'name',
    'color',
    'size',
    'dimensions',
    'material',
    'sku',
    'cost_price',
    'regular_price',
    'sale_price',
    'stock',
    'image',
    'is_active',
])]
class ProductVariation extends Model
{
    use HasFactory;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->regular_price ?? 0);
    }

    public function getDiscountPercentageAttribute(): int
    {
        if ($this->regular_price && $this->sale_price && $this->regular_price > $this->sale_price) {
            return (int) round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
        }

        return 0;
    }

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'regular_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
