<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;

class DemoVariationsAndSeoProductSeeder extends Seeder
{
    /**
     * Run the database seeds for demo categories and products with variations & SEO.
     */
    public function run(): void
    {
        // 1. Create or ensure Categories & Subcategories
        $homeCategory = Category::where('slug', 'home-furniture-diy')->first();
        if (! $homeCategory) {
            $homeCategory = Category::create([
                'name' => 'Home, Furniture & DIY',
                'slug' => 'home-furniture-diy',
                'image' => 'assets/images/furniture/1.png',
                'position' => 0,
                'is_active' => true,
            ]);
        }

        // Subcategory under Home, Furniture & DIY
        $beddingCategory = Category::updateOrCreate(
            ['slug' => 'luxury-bedding-linens'],
            [
                'parent_id' => $homeCategory->id,
                'name' => 'Luxury Bedding & Linens',
                'image' => 'assets/images/furniture/2.png',
                'position' => 1,
                'is_active' => true,
            ]
        );

        // Main Category: Fashion & Apparel
        $fashionCategory = Category::updateOrCreate(
            ['slug' => 'fashion-apparel'],
            [
                'parent_id' => null,
                'name' => 'Fashion & Apparel',
                'image' => 'assets/images/fashion/product/1.jpg',
                'position' => 12,
                'is_active' => true,
            ]
        );

        // Subcategory under Fashion & Apparel
        $mensWearCategory = Category::updateOrCreate(
            ['slug' => 'mens-designer-wear'],
            [
                'parent_id' => $fashionCategory->id,
                'name' => "Men's Designer Wear",
                'image' => 'assets/images/fashion/product/2.jpg',
                'position' => 1,
                'is_active' => true,
            ]
        );

        // 2. Demo Product 1: Signature Egyptian Cotton Percale Duvet Set
        $product1 = Product::updateOrCreate(
            ['slug' => 'signature-egyptian-cotton-percale-duvet-set'],
            [
                'category_id' => $beddingCategory->id,
                'name' => 'Signature Egyptian Cotton Percale Duvet Set',
                'sku' => 'KNK-BED-001',
                'description' => 'Crafted from 100% certified long-staple Egyptian cotton with a crisp 300-thread-count percale weave. Features button closure, internal corner ties, and breathable coolness suitable for year-round luxury comfort.',
                'regular_price' => 180.00,
                'price' => 135.00,
                'cost_price' => 70.00,
                'stock' => 45,
                'unit' => '1 Set',
                'image' => 'assets/images/furniture/1.png',
                'images' => [
                    'assets/images/furniture/1.png',
                    'assets/images/furniture/2.png',
                    'assets/images/furniture/3.png',
                ],
                'size_chart' => "Standard Duvet Sizing:\n- Double: 200 x 200 cm (Pillowcases: 50 x 75 cm)\n- King: 230 x 220 cm (Pillowcases: 50 x 75 cm)\n- Super King: 260 x 220 cm (Pillowcases: 50 x 75 cm)",
                'is_featured' => true,
                'is_top_deal' => true,
                'is_hot_deal' => true,
                'is_active' => true,
                'meta_title' => 'Egyptian Cotton Percale Duvet Set | Kenkie Luxury Bedding',
                'meta_keywords' => 'egyptian cotton, luxury bedding, duvet cover, percale weave, hotel collection',
                'meta_description' => "Experience 5-star hotel comfort at home with Kenkie's 100% long-staple Egyptian cotton percale duvet set with matching oxford pillowcases.",
            ]
        );

        // Product 1 Variations
        $p1Variations = [
            [
                'name' => 'Double / Crisp White',
                'color' => 'Crisp White',
                'size' => 'Double',
                'dimensions' => '200 x 200 cm',
                'material' => '100% Egyptian Cotton',
                'sku' => 'DUV-WHT-DBL',
                'regular_price' => 160.00,
                'sale_price' => 120.00,
                'cost_price' => 60.00,
                'stock' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'King / Crisp White',
                'color' => 'Crisp White',
                'size' => 'King',
                'dimensions' => '230 x 220 cm',
                'material' => '100% Egyptian Cotton',
                'sku' => 'DUV-WHT-KNG',
                'regular_price' => 180.00,
                'sale_price' => 135.00,
                'cost_price' => 70.00,
                'stock' => 18,
                'is_active' => true,
            ],
            [
                'name' => 'Double / Slate Charcoal',
                'color' => 'Slate Charcoal',
                'size' => 'Double',
                'dimensions' => '200 x 200 cm',
                'material' => '100% Egyptian Cotton',
                'sku' => 'DUV-CHR-DBL',
                'regular_price' => 160.00,
                'sale_price' => 120.00,
                'cost_price' => 60.00,
                'stock' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'King / Slate Charcoal',
                'color' => 'Slate Charcoal',
                'size' => 'King',
                'dimensions' => '230 x 220 cm',
                'material' => '100% Egyptian Cotton',
                'sku' => 'DUV-CHR-KNG',
                'regular_price' => 180.00,
                'sale_price' => 135.00,
                'cost_price' => 70.00,
                'stock' => 4, // low stock trigger (< 5)
                'is_active' => true,
            ],
        ];

        foreach ($p1Variations as $varData) {
            ProductVariation::updateOrCreate(
                ['product_id' => $product1->id, 'sku' => $varData['sku']],
                $varData
            );
        }

        // 3. Demo Product 2: Tailored Australian Merino Wool Overcoat
        $product2 = Product::updateOrCreate(
            ['slug' => 'tailored-australian-merino-wool-overcoat'],
            [
                'category_id' => $mensWearCategory->id,
                'name' => 'Tailored Australian Merino Wool Overcoat',
                'sku' => 'KNK-FAS-002',
                'description' => 'A timeless winter staple tailored from premium double-faced Australian Merino Wool. Features sharp notch lapels, horn buttons, dual welt pockets, and a smooth cupro-silk inner lining for effortless layering.',
                'regular_price' => 280.00,
                'price' => 210.00,
                'cost_price' => 110.00,
                'stock' => 22,
                'unit' => '1 Piece',
                'image' => 'assets/images/fashion/product/1.jpg',
                'images' => [
                    'assets/images/fashion/product/1.jpg',
                    'assets/images/fashion/product/2.jpg',
                    'assets/images/fashion/product/3.jpg',
                ],
                'size_chart' => "Overcoat Measurement Guide (inches):\n- M (40): Chest 40\", Length 41\", Sleeve 26\"\n- L (42): Chest 42\", Length 42\", Sleeve 26.5\"\n- XL (44): Chest 44\", Length 43\", Sleeve 27\"",
                'is_featured' => true,
                'is_top_deal' => true,
                'is_hot_deal' => false,
                'is_active' => true,
                'meta_title' => "Tailored Merino Wool Overcoat | Kenkie Men's Collection",
                'meta_keywords' => 'merino wool overcoat, mens tailoring, wool winter coat, luxury menswear, outerwear',
                'meta_description' => "Shop Kenkie's tailored Australian Merino wool overcoat with horn buttons and silk-blend lining. Timeless luxury design for cold-weather elegance.",
            ]
        );

        // Product 2 Variations
        $p2Variations = [
            [
                'name' => 'Medium (40) / Camel Tan',
                'color' => 'Camel Tan',
                'size' => 'M (40)',
                'dimensions' => 'Chest 40" / Length 41"',
                'material' => '100% Merino Wool',
                'sku' => 'COT-CML-M',
                'regular_price' => 280.00,
                'sale_price' => 210.00,
                'cost_price' => 110.00,
                'stock' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Large (42) / Camel Tan',
                'color' => 'Camel Tan',
                'size' => 'L (42)',
                'dimensions' => 'Chest 42" / Length 42"',
                'material' => '100% Merino Wool',
                'sku' => 'COT-CML-L',
                'regular_price' => 280.00,
                'sale_price' => 210.00,
                'cost_price' => 110.00,
                'stock' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Medium (40) / Midnight Navy',
                'color' => 'Midnight Navy',
                'size' => 'M (40)',
                'dimensions' => 'Chest 40" / Length 41"',
                'material' => '100% Merino Wool',
                'sku' => 'COT-NVY-M',
                'regular_price' => 280.00,
                'sale_price' => 210.00,
                'cost_price' => 110.00,
                'stock' => 5, // low stock trigger (<= 5)
                'is_active' => true,
            ],
        ];

        foreach ($p2Variations as $varData) {
            ProductVariation::updateOrCreate(
                ['product_id' => $product2->id, 'sku' => $varData['sku']],
                $varData
            );
        }
    }
}
