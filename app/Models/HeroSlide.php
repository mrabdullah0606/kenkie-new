<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'badge',
        'title',
        'subtitle',
        'description',
        'button_text',
        'button_url',
        'image',
        'position',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];
}
