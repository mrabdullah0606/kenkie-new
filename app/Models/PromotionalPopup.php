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

    protected function casts(): array
    {
        return [
            'delay_seconds' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
