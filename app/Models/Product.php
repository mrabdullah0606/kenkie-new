<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'name',
    'slug',
    'sku',
    'description',
    'price',
    'regular_price',
    'cost_price',
    'stock',
    'unit',
    'image',
    'images',
    'size_chart',
    'is_featured',
    'is_top_deal',
    'is_hot_deal',
    'is_active',
    'meta_title',
    'meta_description',
    'meta_keywords',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function activeVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class)->where('is_active', true);
    }

    public function getHasVariationsAttribute(): bool
    {
        return $this->variations()->count() > 0;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if ($this->regular_price && $this->price && (float) $this->regular_price > (float) $this->price) {
            return (int) round((((float) $this->regular_price - (float) $this->price) / (float) $this->regular_price) * 100);
        }

        return 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= 5;
    }

    /**
     * @return array<int, string>
     */
    public function getGalleryImagesAttribute(): array
    {
        if (is_array($this->images) && count($this->images) > 0) {
            return $this->images;
        }

        if ($this->image) {
            return [$this->image];
        }

        return ['assets/images/vegetable/product/1.png'];
    }

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'price' => 'decimal:2',
            'regular_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'stock' => 'integer',
            'is_featured' => 'boolean',
            'is_top_deal' => 'boolean',
            'is_hot_deal' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
