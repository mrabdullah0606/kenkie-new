<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'slug' => fn (array $attributes): string => Str::slug($attributes['name']).'-'.fake()->unique()->numberBetween(1000, 9999),
            'sku' => fake()->unique()->bothify('SKU-####'),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 100),
            'stock' => fake()->numberBetween(1, 50),
            'unit' => 'each',
            'image' => 'assets/images/vegetable/product/1.png',
            'is_featured' => false,
            'is_active' => true,
        ];
    }
}
