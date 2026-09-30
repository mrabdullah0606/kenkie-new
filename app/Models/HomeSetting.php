<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'hero_badge',
    'hero_title',
    'hero_subtitle',
    'hero_description',
    'hero_button_text',
    'hero_button_url',
    'hero_image',
])]
class HomeSetting extends Model
{
    public static function getSettings(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'hero_badge' => 'Weekend Special Offer',
                'hero_title' => 'Premium Quality Home & Garden Collection',
                'hero_subtitle' => 'Online Shopping Made Easy & Fast',
                'hero_description' => 'Discover our curated selection of quality essentials at best prices!',
                'hero_button_text' => 'Shop Collection',
                'hero_button_url' => '/shop-category',
                'hero_image' => 'assets/images/banner/kenkie-hero-banner.jpg',
            ]
        );
    }
}
