<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'category_id',
    'name',
    'slug',
    'sku',
    'description',
    'price',
    'stock',
    'unit',
    'image',
    'images',
    'is_featured',
    'is_active',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
            'stock' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
