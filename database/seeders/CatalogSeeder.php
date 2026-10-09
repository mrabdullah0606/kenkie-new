<?php

namespace Database\Seeders;

use App\Models\BankOffer;
use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\HomeSetting;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure image directory exists with placeholder fallbacks if needed
        $uploadDir = public_path('assets/uploads/2026/01');
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fallbackSource = public_path('assets/images/product/category/1.jpg');

        $mainCategoriesData = [
            [
                'name' => 'Home, Furniture & DIY',
                'slug' => 'home-furniture-diy',
                'image' => 'assets/images/furniture/1.png',
                'subcategories' => [
                    ['name' => 'Luxury Bedding & Linens', 'slug' => 'luxury-bedding-linens', 'image' => 'assets/images/furniture/1.png'],
                    ['name' => 'Bathroom Accessories', 'slug' => 'bathroom-accessories', 'image' => 'assets/images/furniture/2.png'],
                    ['name' => 'Kitchenware & Cookware', 'slug' => 'kitchenware-cookware', 'image' => 'assets/images/grocery/product/kichen/1.png'],
                    ['name' => 'Accent & Living Furniture', 'slug' => 'accent-living-furniture', 'image' => 'assets/images/furniture/6.png'],
                ],
            ],
            [
                'name' => 'Fashion & Apparel',
                'slug' => 'fashion-apparel',
                'image' => 'assets/images/furniture/11.png',
                'subcategories' => [
                    ['name' => "Men's Designer Wear", 'slug' => 'mens-designer-wear', 'image' => 'assets/images/furniture/11.png'],
                    ['name' => "Women's Collection", 'slug' => 'womens-collection', 'image' => 'assets/images/furniture/5.png'],
                    ['name' => 'Footwear & Accessories', 'slug' => 'footwear-accessories', 'image' => 'assets/images/furniture/3.png'],
                ],
            ],
            [
                'name' => 'Garden & Patio',
                'slug' => 'garden-patio',
                'image' => 'assets/images/furniture/8.png',
                'subcategories' => [
                    ['name' => 'Watering & Garden Hoses', 'slug' => 'watering-garden-hoses', 'image' => 'assets/images/furniture/8.png'],
                    ['name' => 'Outdoor Planters & Pots', 'slug' => 'outdoor-planters-pots', 'image' => 'assets/images/furniture/9.png'],
                    ['name' => 'Solar & Garden Lighting', 'slug' => 'solar-garden-lighting', 'image' => 'assets/images/furniture/10.png'],
                ],
            ],
            [
                'name' => 'Vehicle Parts & Accessories',
                'slug' => 'vehicle-parts-accessories',
                'image' => 'assets/images/furniture/14.png',
                'subcategories' => [
                    ['name' => 'Car Cleaning & Care', 'slug' => 'car-cleaning-care', 'image' => 'assets/images/grocery/product/kichen/3.png'],
                    ['name' => 'Phone Mounts & Holders', 'slug' => 'car-phone-mounts-holders', 'image' => 'assets/images/furniture/13.png'],
                    ['name' => 'Tyre & Maintenance Tools', 'slug' => 'tyre-maintenance-tools', 'image' => 'assets/images/furniture/14.png'],
                ],
            ],
            [
                'name' => 'Sporting Goods',
                'slug' => 'sporting-goods',
                'image' => 'assets/images/grocery/product/kichen/4.png',
                'subcategories' => [
                    ['name' => 'Yoga & Fitness Mats', 'slug' => 'yoga-fitness-mats', 'image' => 'assets/images/grocery/product/kichen/4.png'],
                    ['name' => 'Resistance & Home Gym', 'slug' => 'resistance-home-gym', 'image' => 'assets/images/grocery/product/kichen/5.png'],
                    ['name' => 'Camping & Outdoors', 'slug' => 'camping-outdoors', 'image' => 'assets/images/furniture/6.png'],
                ],
            ],
            [
                'name' => 'Pet Supplies',
                'slug' => 'pet-supplies',
                'image' => 'assets/images/furniture/2.png',
                'subcategories' => [
                    ['name' => 'Pet Beds & Furniture', 'slug' => 'pet-beds-furniture', 'image' => 'assets/images/furniture/2.png'],
                    ['name' => 'Interactive Pet Toys', 'slug' => 'interactive-pet-toys', 'image' => 'assets/images/grocery/product/personal-care/1.png'],
                    ['name' => 'Bowls & Feeders', 'slug' => 'bowls-feeders', 'image' => 'assets/images/grocery/product/kichen/6.png'],
                ],
            ],
            [
                'name' => 'Mobile Phones & Communication',
                'slug' => 'mobile-phones-communication',
                'image' => 'assets/images/grocery/product/personal-care/2.png',
                'subcategories' => [
                    ['name' => 'Wireless Chargers & Docks', 'slug' => 'wireless-chargers-docks', 'image' => 'assets/images/grocery/product/personal-care/2.png'],
                    ['name' => 'Power Banks & Batteries', 'slug' => 'power-banks-batteries', 'image' => 'assets/images/grocery/product/kichen/7.png'],
                    ['name' => 'Cables & Wall Chargers', 'slug' => 'cables-wall-chargers', 'image' => 'assets/images/grocery/product/personal-care/4.png'],
                ],
            ],
            [
                'name' => 'Health & Beauty',
                'slug' => 'health-beauty',
                'image' => 'assets/images/grocery/product/personal-care/6.png',
                'subcategories' => [
                    ['name' => 'Grooming & Trimmers', 'slug' => 'grooming-trimmers', 'image' => 'assets/images/grocery/product/personal-care/5.png'],
                    ['name' => 'Electric Toothbrushes', 'slug' => 'electric-toothbrushes', 'image' => 'assets/images/grocery/product/personal-care/6.png'],
                    ['name' => 'Aromatherapy & Diffusers', 'slug' => 'aromatherapy-diffusers', 'image' => 'assets/images/grocery/product/personal-care/8.png'],
                ],
            ],
            [
                'name' => 'Computers/Tablets & Networking',
                'slug' => 'computers-tablets-networking',
                'image' => 'assets/images/furniture/10.png',
                'subcategories' => [
                    ['name' => 'Laptop Stands & Desks', 'slug' => 'laptop-stands-desks', 'image' => 'assets/images/furniture/1.png'],
                    ['name' => 'Multiport Hubs & Adapters', 'slug' => 'multiport-hubs-adapters', 'image' => 'assets/images/furniture/13.png'],
                    ['name' => 'Keyboards & Mice', 'slug' => 'keyboards-mice', 'image' => 'assets/images/furniture/14.png'],
                ],
            ],
            [
                'name' => 'Collectables',
                'slug' => 'collectables',
                'image' => 'assets/images/furniture/9.png',
                'subcategories' => [
                    ['name' => 'Wall Clocks & Timepieces', 'slug' => 'wall-clocks-timepieces', 'image' => 'assets/images/furniture/8.png'],
                    ['name' => 'Artisan Trays & Accents', 'slug' => 'artisan-trays-accents', 'image' => 'assets/images/furniture/7.png'],
                ],
            ],
            [
                'name' => 'Crafts',
                'slug' => 'crafts',
                'image' => 'assets/images/furniture/6.png',
                'subcategories' => [
                    ['name' => 'Acrylic & Fine Art Paints', 'slug' => 'acrylic-fine-art-paints', 'image' => 'assets/images/grocery/product/chemist/2.png'],
                    ['name' => 'Macrame Cords & Ropes', 'slug' => 'macrame-cords-ropes', 'image' => 'assets/images/grocery/product/chemist/3.png'],
                ],
            ],
            [
                'name' => 'Sound & Vision',
                'slug' => 'sound-vision',
                'image' => 'assets/images/furniture/11.png',
                'subcategories' => [
                    ['name' => 'Noise Cancelling Headphones', 'slug' => 'noise-cancelling-headphones', 'image' => 'assets/images/furniture/11.png'],
                    ['name' => 'Bluetooth Speakers', 'slug' => 'bluetooth-speakers', 'image' => 'assets/images/grocery/product/personal-care/2.png'],
                    ['name' => 'Microphones & Streaming', 'slug' => 'microphones-streaming', 'image' => 'assets/images/grocery/product/personal-care/4.png'],
                    ['name' => 'Home Cinema Projectors', 'slug' => 'home-cinema-projectors', 'image' => 'assets/images/furniture/10.png'],
                ],
            ],
        ];

        $allValidSlugs = [];
        foreach ($mainCategoriesData as $pos => $mainData) {
            $allValidSlugs[] = $mainData['slug'];
            $mainCat = Category::updateOrCreate(
                ['slug' => $mainData['slug']],
                [
                    'parent_id' => null,
                    'name' => $mainData['name'],
                    'image' => $mainData['image'],
                    'position' => $pos,
                    'is_active' => true,
                ]
            );

            if (! empty($mainData['subcategories'])) {
                foreach ($mainData['subcategories'] as $subPos => $subData) {
                    $allValidSlugs[] = $subData['slug'];
                    Category::updateOrCreate(
                        ['slug' => $subData['slug']],
                        [
                            'parent_id' => $mainCat->id,
                            'name' => $subData['name'],
                            'image' => $subData['image'],
                            'position' => $subPos + 1,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        // Clean up categories not in the defined taxonomy
        Category::query()->whereNotIn('slug', $allValidSlugs)->delete();

        $categoryIds = Category::query()->pluck('id', 'slug');

        $products = [
            // 1. Home, Furniture & DIY
            [
                'slug' => 'alto-organic-cotton-gauze-sheet',
                'name' => 'Alto Organic Cotton Gauze Sheet',
                'category_slug' => 'home-furniture-diy',
                'price' => 58.00,
                'unit' => '1 Set',
                'description' => 'Crafted from 100% organic cotton gauze, this ultra-soft sheet set is gentle on sensitive skin and perfect for warm nights.',
                'image' => 'assets/images/furniture/1.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'blue-white-ceramic-bath-accessories',
                'name' => 'Blue White Ceramic Bath Accessories',
                'category_slug' => 'home-furniture-diy',
                'price' => 88.00,
                'unit' => '1 Set',
                'description' => 'Elevate your bathroom with this elegant blue and white ceramic accessory set. Includes dispenser, toothbrush holder, soap dish, and tumbler.',
                'image' => 'assets/images/furniture/2.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'lateral-natural-teak-bath-mat',
                'name' => 'Lateral Natural Teak Bath Mat',
                'category_slug' => 'home-furniture-diy',
                'price' => 112.00,
                'unit' => '1 Unit',
                'description' => 'Handcrafted from sustainably sourced teak wood, this bath mat adds a spa-like feel with non-slip rubber feet.',
                'image' => 'assets/images/furniture/3.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'cast-iron-double-sided-griddle-pan',
                'name' => 'Cast Iron Double Sided Griddle Pan Reversible Grill Plate',
                'category_slug' => 'home-furniture-diy',
                'price' => 22.99,
                'unit' => '1 Unit',
                'description' => 'Heavy duty reversible cast iron griddle plate for BBQ stove, hob, and oven cooking with ribbed and flat sides.',
                'image' => 'assets/images/grocery/product/kichen/1.png',
                'is_featured' => true,
            ],
            [
                'slug' => '2-x-shoe-horn-unisex-shoe-horn-31cm',
                'name' => '2 x Shoe Horn Unisex Shoe Horn 31 cm No More Bends ABS',
                'category_slug' => 'home-furniture-diy',
                'price' => 4.99,
                'unit' => '2 Pack',
                'description' => 'Convenient durable 31cm unisex shoe horn pair made from high grade smooth ABS with ergonomic loop grip.',
                'image' => 'assets/images/furniture/4.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'vegetable-chopper-mandoline-cutter',
                'name' => 'Multi-Blade Vegetable Chopper Mandoline Slicer Cutter Container',
                'category_slug' => 'home-furniture-diy',
                'price' => 16.99,
                'unit' => '1 Set',
                'description' => 'All-in-one kitchen vegetable chopper and mandoline slicer with container, draining basket, and sharp stainless steel blades.',
                'image' => 'assets/images/grocery/product/kichen/2.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'bowtie-green-boucle-chair',
                'name' => 'Bowtie Green Boucle Accent Chair',
                'category_slug' => 'home-furniture-diy',
                'price' => 299.00,
                'unit' => '1 Unit',
                'description' => 'Curved bowtie accent chair upholstered in plush green boucle fabric with solid wood tapered legs.',
                'image' => 'assets/images/furniture/6.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'damon-aged-brass-cabinet-knob',
                'name' => 'Damon Aged Brass Cabinet Knob & Pull Handle',
                'category_slug' => 'home-furniture-diy',
                'price' => 55.00,
                'unit' => '4 Pack',
                'description' => 'Artisanal aged brass hardware knobs for kitchen and bathroom cabinets with durable antiqued patina finish.',
                'image' => 'assets/images/furniture/7.png',
                'is_featured' => false,
            ],

            // 2. Garden & Patio
            [
                'slug' => 'wall-mounted-retractable-garden-hose',
                'name' => 'Wall-Mounted Retractable Garden Hose Reel 100ft',
                'category_slug' => 'garden-patio',
                'price' => 75.00,
                'unit' => '1 Unit',
                'description' => 'Auto-rewind retractable garden hose reel with multi-pattern spray nozzle and 180-degree swivel wall bracket.',
                'image' => 'assets/images/furniture/8.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'wooden-flower-planter-box-outdoor',
                'name' => 'Handcrafted Wooden Flower Planter Box Outdoor',
                'category_slug' => 'garden-patio',
                'price' => 34.99,
                'unit' => '1 Unit',
                'description' => 'Solid cedar wood rectangular planter trough for flowers, herbs, and patio greenery with drainage holes.',
                'image' => 'assets/images/furniture/9.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'solar-powered-led-garden-lights-6pk',
                'name' => 'Solar Powered LED Garden Pathway Lights 6-Pack',
                'category_slug' => 'garden-patio',
                'price' => 28.50,
                'unit' => '6 Pack',
                'description' => 'Waterproof stainless steel solar stake lights for garden pathways, borders, and lawn lighting with dusk-to-dawn sensors.',
                'image' => 'assets/images/furniture/10.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'heavy-duty-garden-tool-set-5pc',
                'name' => 'Heavy Duty Ergonomic Garden Tool Set 5-Piece',
                'category_slug' => 'garden-patio',
                'price' => 24.99,
                'unit' => '5 Piece Set',
                'description' => 'Cast aluminum garden hand tools with comfortable soft rubber grips, including trowel, transplanter, cultivator, and pruner.',
                'image' => 'assets/images/furniture/11.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'weatherproof-patio-furniture-cover',
                'name' => 'Heavy Duty Weatherproof Patio Furniture Cover',
                'category_slug' => 'garden-patio',
                'price' => 32.00,
                'unit' => '1 Unit',
                'description' => '600D Oxford fabric waterproof cover with windproof buckle straps for outdoor table and chair sets.',
                'image' => 'assets/images/furniture/12.png',
                'is_featured' => false,
            ],

            // 3. Vehicle Parts & Accessories
            [
                'slug' => 'high-power-12v-car-vacuum-cleaner',
                'name' => 'High Power 12V Car Vacuum Cleaner Portable Handheld',
                'category_slug' => 'vehicle-parts-accessories',
                'price' => 21.99,
                'unit' => '1 Unit',
                'description' => 'Compact car vacuum cleaner with strong cyclonic suction, washable HEPA filter, and crevice extension tools.',
                'image' => 'assets/images/grocery/product/kichen/3.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'wireless-magnetic-car-phone-mount',
                'name' => 'Wireless Fast Charging Magnetic Car Phone Mount',
                'category_slug' => 'vehicle-parts-accessories',
                'price' => 18.50,
                'unit' => '1 Unit',
                'description' => '15W fast wireless charging magnetic air vent mount with 360-degree ball rotation for smartphones.',
                'image' => 'assets/images/furniture/13.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'digital-tyre-pressure-gauge-lcd',
                'name' => 'Digital Tyre Pressure Gauge with Backlit LCD Display',
                'category_slug' => 'vehicle-parts-accessories',
                'price' => 12.99,
                'unit' => '1 Unit',
                'description' => 'Accurate 150 PSI digital tire gauge with lighted nozzle and non-slip grip for cars, bikes, and vans.',
                'image' => 'assets/images/furniture/14.png',
                'is_featured' => false,
            ],
            [
                'slug' => '4-wheel-furniture-moving-tool-set',
                'name' => '4-Wheel Heavy Duty Vehicle & Furniture Moving Tool Set',
                'category_slug' => 'vehicle-parts-accessories',
                'price' => 55.00,
                'unit' => '4 Piece Set',
                'description' => 'Heavy duty transport dollies supporting up to 330 lbs each with 360-degree rotating wheels.',
                'image' => 'assets/images/furniture/5.png',
                'is_featured' => false,
            ],

            // 4. Sporting Goods
            [
                'slug' => 'non-slip-yoga-pilates-mat-strap',
                'name' => 'Non-Slip High Density Yoga & Pilates Mat with Strap',
                'category_slug' => 'sporting-goods',
                'price' => 26.00,
                'unit' => '1 Unit',
                'description' => 'Eco-friendly TPE yoga mat with dual-sided non-slip texture and alignment guides for workouts.',
                'image' => 'assets/images/grocery/product/kichen/4.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'resistance-exercise-bands-set',
                'name' => 'Resistance Exercise Bands Set with Door Anchor & Handles',
                'category_slug' => 'sporting-goods',
                'price' => 19.99,
                'unit' => '11 Piece Set',
                'description' => 'Stackable resistance workout bands up to 150 lbs for strength training, physical therapy, and home fitness.',
                'image' => 'assets/images/grocery/product/kichen/5.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'insulated-stainless-steel-water-bottle-1l',
                'name' => 'Insulated Stainless Steel Sports Water Bottle 1 Litre',
                'category_slug' => 'sporting-goods',
                'price' => 16.50,
                'unit' => '1 Unit',
                'description' => 'Double-wall vacuum insulated flask keeping drinks cold for 24h or hot for 12h with leakproof straw lid.',
                'image' => 'assets/images/grocery/product/drink/1.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'lightweight-folding-camping-chair',
                'name' => 'Lightweight Compact Folding Camping Chair',
                'category_slug' => 'sporting-goods',
                'price' => 27.50,
                'unit' => '1 Unit',
                'description' => 'Heavy-duty aluminum portable camping chair with cup holder and compact storage bag.',
                'image' => 'assets/images/furniture/6.png',
                'is_featured' => false,
            ],

            // 5. Pet Supplies
            [
                'slug' => 'orthopedic-memory-foam-dog-bed',
                'name' => 'Orthopedic Memory Foam Pet Bed with Washable Cover',
                'category_slug' => 'pet-supplies',
                'price' => 45.00,
                'unit' => '1 Unit',
                'description' => 'Plush orthopedic dog and cat bed with supportive memory foam base and removable water-resistant cover.',
                'image' => 'assets/images/furniture/2.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'interactive-automatic-laser-cat-toy',
                'name' => 'Interactive Automatic Rotating Laser Cat Toy',
                'category_slug' => 'pet-supplies',
                'price' => 17.99,
                'unit' => '1 Unit',
                'description' => 'Hands-free automatic 360-degree laser pointer toy with random patterns and timer modes for cats.',
                'image' => 'assets/images/grocery/product/personal-care/1.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'stainless-steel-double-pet-bowls',
                'name' => 'Stainless Steel No-Spill Double Pet Food & Water Bowls',
                'category_slug' => 'pet-supplies',
                'price' => 15.50,
                'unit' => '1 Set',
                'description' => 'Non-skid silicone base with two removable rust-proof stainless steel feeding dishes.',
                'image' => 'assets/images/grocery/product/kichen/6.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'heavy-duty-retractable-dog-leash-5m',
                'name' => 'Heavy Duty Retractable Dog Leash 5 Metres with Brake',
                'category_slug' => 'pet-supplies',
                'price' => 13.99,
                'unit' => '1 Unit',
                'description' => 'Tangle-free 360-degree nylon ribbon lead with quick-lock single-button braking mechanism.',
                'image' => 'assets/images/grocery/product/personal-care/3.png',
                'is_featured' => false,
            ],

            // 6. Mobile Phones & Communication
            [
                'slug' => '3-in-1-magnetic-wireless-charger-station',
                'name' => '3-in-1 Foldable Magnetic Wireless Charging Station Stand',
                'category_slug' => 'mobile-phones-communication',
                'price' => 39.99,
                'unit' => '1 Unit',
                'description' => 'Simultaneous fast charging station for phone, smartwatch, and earbuds with LED charging indicator.',
                'image' => 'assets/images/grocery/product/personal-care/2.png',
                'is_featured' => true,
            ],
            [
                'slug' => '20000mah-fast-charging-portable-power-bank',
                'name' => '20000mAh Fast Charging Portable Power Bank PD 20W',
                'category_slug' => 'mobile-phones-communication',
                'price' => 28.00,
                'unit' => '1 Unit',
                'description' => 'High capacity ultra-compact external battery pack with dual USB-A and USB-C output ports.',
                'image' => 'assets/images/grocery/product/kichen/7.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'aluminum-adjustable-phone-tablet-stand',
                'name' => 'Universal Adjustable Aluminum Desktop Phone & Tablet Stand',
                'category_slug' => 'mobile-phones-communication',
                'price' => 14.99,
                'unit' => '1 Unit',
                'description' => 'Sturdy weighted metal desk cradle with anti-slip rubber pads for iPhone, iPad, and Android devices.',
                'image' => 'assets/images/furniture/9.png',
                'is_featured' => false,
            ],
            [
                'slug' => '65w-gan-fast-wall-charger-3port',
                'name' => 'High-Speed USB-C GaN 65W Wall Charger 3-Port Fast Plug',
                'category_slug' => 'mobile-phones-communication',
                'price' => 24.99,
                'unit' => '1 Unit',
                'description' => 'Next-gen GaN fast charging brick with 2x Type-C and 1x USB-A ports for laptops, phones, and tablets.',
                'image' => 'assets/images/grocery/product/personal-care/4.png',
                'is_featured' => false,
            ],

            // 7. Health & Beauty
            [
                'slug' => 'kenkie-ear-and-nose-hair-trimmer-9300-rpm',
                'name' => 'Kenkie Ear and Nose Hair Trimmer 9300 RPM Dual Edge Blades',
                'category_slug' => 'health-beauty',
                'price' => 8.99,
                'unit' => '1 Unit',
                'description' => 'Professional precision 9300 RPM micro-motor trimmer with painless stainless steel dual-edge blades and LED light.',
                'image' => 'assets/images/grocery/product/personal-care/5.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'sonic-electric-toothbrush-4-heads',
                'name' => 'Sonic Electric Toothbrush with 4 Replacement Brush Heads',
                'category_slug' => 'health-beauty',
                'price' => 32.00,
                'unit' => '1 Set',
                'description' => '40,000 VPM sonic motor rechargeable toothbrush with 5 cleaning modes and 2-minute smart timer.',
                'image' => 'assets/images/grocery/product/personal-care/6.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'professional-hair-clipper-beard-trimmer',
                'name' => 'Professional Cordless Hair Clipper & Beard Trimmer Kit',
                'category_slug' => 'health-beauty',
                'price' => 27.50,
                'unit' => '1 Kit',
                'description' => 'Rechargeable precision hair grooming set with titanium ceramic blades and multiple guide combs.',
                'image' => 'assets/images/grocery/product/personal-care/7.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'ultrasonic-essential-oil-diffuser-500ml',
                'name' => 'Aromatherapy Ultrasonic Essential Oil Diffuser 500ml',
                'category_slug' => 'health-beauty',
                'price' => 22.00,
                'unit' => '1 Unit',
                'description' => 'Cool mist aroma humidifier with 7 soothing ambient LED light colours and auto shut-off function.',
                'image' => 'assets/images/grocery/product/personal-care/8.png',
                'is_featured' => false,
            ],

            // 8. Computers/Tablets & Networking
            [
                'slug' => 'portable-folding-bed-laptop-desk-table',
                'name' => 'Portable Folding Bed Laptop Desk Table with Cup Holder',
                'category_slug' => 'computers-tablets-networking',
                'price' => 34.99,
                'unit' => '1 Unit',
                'description' => 'Multi-functional aluminum foldable laptop bed table with non-slip legs, tablet slot, and carry handle.',
                'image' => 'assets/images/furniture/1.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'ergonomic-aluminum-laptop-cooling-stand',
                'name' => 'Adjustable Ergonomic Aluminum Laptop Cooling Stand',
                'category_slug' => 'computers-tablets-networking',
                'price' => 25.00,
                'unit' => '1 Unit',
                'description' => 'Foldable ventilated riser supporting up to 17-inch laptops with 6-level height adjustment.',
                'image' => 'assets/images/furniture/10.png',
                'is_featured' => true,
            ],
            [
                'slug' => '7-in-1-dual-4k-hdmi-usbc-hub',
                'name' => '7-in-1 Dual 4K HDMI USB-C Multiport Hub Adapter',
                'category_slug' => 'computers-tablets-networking',
                'price' => 29.99,
                'unit' => '1 Unit',
                'description' => 'High speed dongle with dual 4K HDMI ports, 100W PD charging, SD/TF card reader, and USB 3.0 ports.',
                'image' => 'assets/images/furniture/13.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'wireless-bluetooth-keyboard-mouse-combo',
                'name' => 'Slim Rechargeable Wireless Bluetooth Keyboard & Mouse Combo',
                'category_slug' => 'computers-tablets-networking',
                'price' => 32.50,
                'unit' => '1 Set',
                'description' => 'Quiet low-profile keys with multi-device Bluetooth and 2.4GHz wireless connectivity for Mac and Windows.',
                'image' => 'assets/images/furniture/14.png',
                'is_featured' => false,
            ],

            // 9. Collectables
            [
                'slug' => 'jomparis-wall-clock-12inch',
                'name' => 'Jomparis Wall Clock 12 Inch Silent Movement',
                'category_slug' => 'collectables',
                'price' => 188.00,
                'unit' => '1 Unit',
                'description' => '12-inch minimalist wall clock with clean numerals and whisper-quiet sweep mechanism.',
                'image' => 'assets/images/furniture/8.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'wall-clock-unique-motifs',
                'name' => 'Decorative Wall Clock with Artisan Motifs',
                'category_slug' => 'collectables',
                'price' => 145.00,
                'unit' => '1 Unit',
                'description' => 'Handcrafted wall clock featuring painted floral motifs and rustic wrought iron frame.',
                'image' => 'assets/images/furniture/9.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'teo-square-cast-decorative-tray',
                'name' => 'Teo Square Cast Aluminum Decorative Tray Bronze',
                'category_slug' => 'collectables',
                'price' => 95.00,
                'unit' => '1 Unit',
                'description' => 'Solid aluminum square tray with hand-rubbed antique bronze finish for coffee tables and consoles.',
                'image' => 'assets/images/furniture/7.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'vintage-cast-iron-mechanical-coin-bank',
                'name' => 'Vintage Cast Iron Mechanical Coin Bank Replica',
                'category_slug' => 'collectables',
                'price' => 38.50,
                'unit' => '1 Unit',
                'description' => 'Heavy antique-style mechanical money box with moving lever action and hand-painted finish.',
                'image' => 'assets/images/furniture/4.png',
                'is_featured' => false,
            ],

            // 10. Crafts
            [
                'slug' => 'floral-cutout-folding-room-divider',
                'name' => 'Floral Cutout Folding 4-Panel Room Divider',
                'category_slug' => 'crafts',
                'price' => 195.00,
                'unit' => '1 Unit',
                'description' => 'Decorative 4-panel folding partition screen with intricate laser-cut floral filigree pattern.',
                'image' => 'assets/images/furniture/6.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'premium-acrylic-paint-set-24-colours',
                'name' => 'Premium Acrylic Paint Set 24 Vibrant Colours',
                'category_slug' => 'crafts',
                'price' => 18.50,
                'unit' => '24 Tube Set',
                'description' => 'Richly pigmented artist grade non-toxic acrylic paints for canvas, wood, fabric, and ceramic art.',
                'image' => 'assets/images/grocery/product/chemist/2.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'hand-carved-wooden-stamp-printing-blocks',
                'name' => 'Hand-Carved Wooden Stamp Printing Blocks 5-Piece',
                'category_slug' => 'crafts',
                'price' => 14.50,
                'unit' => '5 Piece Set',
                'description' => 'Traditional artisan wooden printing blocks for textile, pottery, clay, and scrapbooking designs.',
                'image' => 'assets/images/furniture/3.png',
                'is_featured' => false,
            ],
            [
                'slug' => '100-percent-cotton-macrame-cord-3mm-200m',
                'name' => '100% Natural Cotton Macrame Cord 3mm 200 Metres',
                'category_slug' => 'crafts',
                'price' => 12.99,
                'unit' => '200m Roll',
                'description' => 'Soft 4-strand twisted unbleached natural cotton rope for plant hangers, wall hangings, and craft projects.',
                'image' => 'assets/images/grocery/product/chemist/3.png',
                'is_featured' => false,
            ],

            // 11. Sound & Vision
            [
                'slug' => 'wireless-active-noise-cancelling-headphones',
                'name' => 'Wireless Active Noise Cancelling Over-Ear Headphones',
                'category_slug' => 'sound-vision',
                'price' => 68.00,
                'unit' => '1 Unit',
                'description' => 'Hybrid ANC Bluetooth 5.3 headphones with deep bass, 40-hour battery life, and crystal clear built-in mic.',
                'image' => 'assets/images/furniture/11.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'waterproof-portable-bluetooth-speaker-bass',
                'name' => 'Waterproof Portable Bluetooth Speaker with Bass Boost',
                'category_slug' => 'sound-vision',
                'price' => 36.50,
                'unit' => '1 Unit',
                'description' => 'IPX7 waterproof outdoor wireless speaker with 360-degree surround sound and 24-hour playtime.',
                'image' => 'assets/images/grocery/product/personal-care/2.png',
                'is_featured' => true,
            ],
            [
                'slug' => 'studio-condenser-usb-microphone-kit',
                'name' => 'Studio Condenser USB Microphone Kit with Boom Arm',
                'category_slug' => 'sound-vision',
                'price' => 42.00,
                'unit' => '1 Kit',
                'description' => 'Cardioid podcasting mic with shock mount, pop filter, desk clamp arm, and zero-latency monitoring.',
                'image' => 'assets/images/grocery/product/personal-care/4.png',
                'is_featured' => false,
            ],
            [
                'slug' => 'mini-full-hd-1080p-home-projector',
                'name' => 'Mini Full HD 1080P Home Cinema Projector Portable',
                'category_slug' => 'sound-vision',
                'price' => 89.00,
                'unit' => '1 Unit',
                'description' => 'Compact 9000 lumens movie projector with HDMI, USB, and screen mirroring support up to 200 inches.',
                'image' => 'assets/images/furniture/10.png',
                'is_featured' => false,
            ],
        ];

        // Wipe old products that are not in the new list
        $newProductSlugs = array_column($products, 'slug');
        Product::query()->whereNotIn('slug', $newProductSlugs)->delete();

        foreach ($products as $index => $item) {
            $catSlug = $item['category_slug'];
            $categoryId = $categoryIds[$catSlug] ?? null;

            if (! $categoryId) {
                continue;
            }

            // Copy fallback image if referenced file doesn't exist on disk yet
            $fullPath = public_path($item['image']);
            if (! file_exists($fullPath) && file_exists($fallbackSource)) {
                $dir = dirname($fullPath);
                if (! is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                @copy($fallbackSource, $fullPath);
            }

            $sku = strtoupper(substr(str_replace('-', '', $catSlug), 0, 3)).'-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);

            $mainImage = $item['image'];
            $galleryImages = [$mainImage];
            $baseName = basename($mainImage, '.jpg');
            $prefix = explode('_', $baseName)[0] ?? 'Living';

            for ($i = 1; $i <= 3; $i++) {
                $altNum = (($i * 2 + $index) % 9) + 1;
                $altImage = "assets/uploads/2026/01/{$prefix}_{$altNum}.jpg";
                if (! in_array($altImage, $galleryImages)) {
                    $galleryImages[] = $altImage;
                }
            }

            $isTopDeal = $index < 6;
            $isHotDeal = $index === 0;

            Product::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $categoryId,
                    'name' => $item['name'],
                    'sku' => $sku,
                    'description' => $item['description'],
                    'price' => (float) $item['price'],
                    'stock' => rand(20, 80),
                    'unit' => $item['unit'] ?? '1 Unit',
                    'image' => $mainImage,
                    'images' => $galleryImages,
                    'is_featured' => $item['is_featured'] ?? false,
                    'is_top_deal' => $isTopDeal,
                    'is_hot_deal' => $isHotDeal,
                    'is_active' => true,
                ]
            );
        }

        // Initialize Home Settings
        HomeSetting::getSettings();

        // Initialize Home Banners
        $defaultBanners = [
            [
                'title' => 'Home & Furniture',
                'subtitle' => 'New Arrivals',
                'button_text' => 'Shop Now',
                'button_url' => '/shop-category?category=home-furniture-diy',
                'image' => 'assets/images/banner/kenkie-promo-home.jpg',
                'position' => 0,
                'is_active' => true,
            ],
            [
                'title' => 'Garden & Patio',
                'subtitle' => 'Outdoor Living',
                'button_text' => 'Shop Now',
                'button_url' => '/shop-category?category=garden-patio',
                'image' => 'assets/images/banner/kenkie-promo-garden.jpg',
                'position' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Health & Beauty',
                'subtitle' => 'Personal Care',
                'button_text' => 'Shop Now',
                'button_url' => '/shop-category?category=health-beauty',
                'image' => 'assets/images/banner/kenkie-promo-home.jpg',
                'position' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Sound & Vision',
                'subtitle' => 'Electronics',
                'button_text' => 'Shop Now',
                'button_url' => '/shop-category?category=sound-vision',
                'image' => 'assets/images/banner/kenkie-promo-garden.jpg',
                'position' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($defaultBanners as $banner) {
            HomeBanner::firstOrCreate(['title' => $banner['title']], $banner);
        }

        // Initialize Bank Offers
        $defaultOffers = [
            [
                'title' => 'GET 10% OFF',
                'subtitle' => 'When you spend $20',
                'validity' => 'Valid for 30 days',
                'code' => 'KENKIE10',
                'color_theme' => 'theme-1',
                'bank_image' => 'assets/images/grocery/bank/name/1.png',
                'position' => 0,
                'is_active' => true,
            ],
            [
                'title' => 'FREE SHIPPING',
                'subtitle' => 'On orders over $50',
                'validity' => 'Valid for 30 days',
                'code' => 'FREESHIP',
                'color_theme' => 'theme-2',
                'bank_image' => 'assets/images/grocery/bank/name/2.png',
                'position' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'SAVE $15',
                'subtitle' => 'When you spend $100',
                'validity' => 'Valid for 30 days',
                'code' => 'SAVE15',
                'color_theme' => 'theme-3',
                'bank_image' => 'assets/images/grocery/bank/name/3.png',
                'position' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($defaultOffers as $offer) {
            BankOffer::firstOrCreate(['code' => $offer['code']], $offer);
        }
    }
}
