<?php

namespace Database\Seeders;

use App\Models\Category;
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

        $categoriesData = [
            ['name' => 'Home, Furniture & DIY', 'slug' => 'home-furniture-diy', 'image' => 'assets/uploads/2026/01/Bathroom_1.jpg'],
            ['name' => 'Garden & Patio', 'slug' => 'garden-patio', 'image' => 'assets/uploads/2026/01/Living_1.jpg'],
            ['name' => 'Vehicle Parts & Accessories', 'slug' => 'vehicle-parts-accessories', 'image' => 'assets/uploads/2026/01/Office_1.jpg'],
            ['name' => 'Sporting Goods', 'slug' => 'sporting-goods', 'image' => 'assets/uploads/2026/01/Office_2.jpg'],
            ['name' => 'Pet Supplies', 'slug' => 'pet-supplies', 'image' => 'assets/uploads/2026/01/Bathroom_2.jpg'],
            ['name' => 'Mobile Phones & Communication', 'slug' => 'mobile-phones-communication', 'image' => 'assets/uploads/2026/01/Office_3.jpg'],
            ['name' => 'Health & Beauty', 'slug' => 'health-beauty', 'image' => 'assets/uploads/2026/01/Bathroom_3.jpg'],
            ['name' => 'Computers/Tablets & Networking', 'slug' => 'computers-tablets-networking', 'image' => 'assets/uploads/2026/01/Office_4.jpg'],
            ['name' => 'Collectables', 'slug' => 'collectables', 'image' => 'assets/uploads/2026/01/Living_2.jpg'],
            ['name' => 'Crafts', 'slug' => 'crafts', 'image' => 'assets/uploads/2026/01/Living_3.jpg'],
            ['name' => 'Sound & Vision', 'slug' => 'sound-vision', 'image' => 'assets/uploads/2026/01/Office_5.jpg'],
        ];

        // Wipe old categories that are not in the new list
        $newSlugs = array_column($categoriesData, 'slug');
        Category::query()->whereNotIn('slug', $newSlugs)->delete();

        foreach ($categoriesData as $position => $attributes) {
            Category::updateOrCreate(
                ['slug' => $attributes['slug']],
                [...$attributes, 'position' => $position, 'is_active' => true],
            );
        }

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
                'image' => 'assets/uploads/2026/01/Bathroom_3.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'blue-white-ceramic-bath-accessories',
                'name' => 'Blue White Ceramic Bath Accessories',
                'category_slug' => 'home-furniture-diy',
                'price' => 88.00,
                'unit' => '1 Set',
                'description' => 'Elevate your bathroom with this elegant blue and white ceramic accessory set. Includes dispenser, toothbrush holder, soap dish, and tumbler.',
                'image' => 'assets/uploads/2026/01/Bathroom_4.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'lateral-natural-teak-bath-mat',
                'name' => 'Lateral Natural Teak Bath Mat',
                'category_slug' => 'home-furniture-diy',
                'price' => 112.00,
                'unit' => '1 Unit',
                'description' => 'Handcrafted from sustainably sourced teak wood, this bath mat adds a spa-like feel with non-slip rubber feet.',
                'image' => 'assets/uploads/2026/01/Bathroom_1.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'cast-iron-double-sided-griddle-pan',
                'name' => 'Cast Iron Double Sided Griddle Pan Reversible Grill Plate',
                'category_slug' => 'home-furniture-diy',
                'price' => 22.99,
                'unit' => '1 Unit',
                'description' => 'Heavy duty reversible cast iron griddle plate for BBQ stove, hob, and oven cooking with ribbed and flat sides.',
                'image' => 'assets/uploads/2026/01/Kitchen_8.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => '2-x-shoe-horn-unisex-shoe-horn-31cm',
                'name' => '2 x Shoe Horn Unisex Shoe Horn 31 cm No More Bends ABS',
                'category_slug' => 'home-furniture-diy',
                'price' => 4.99,
                'unit' => '2 Pack',
                'description' => 'Convenient durable 31cm unisex shoe horn pair made from high grade smooth ABS with ergonomic loop grip.',
                'image' => 'assets/uploads/2026/01/Bathroom_6.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'vegetable-chopper-mandoline-cutter',
                'name' => 'Multi-Blade Vegetable Chopper Mandoline Slicer Cutter Container',
                'category_slug' => 'home-furniture-diy',
                'price' => 16.99,
                'unit' => '1 Set',
                'description' => 'All-in-one kitchen vegetable chopper and mandoline slicer with container, draining basket, and sharp stainless steel blades.',
                'image' => 'assets/uploads/2026/01/Kitchen_2.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'bowtie-green-boucle-chair',
                'name' => 'Bowtie Green Boucle Accent Chair',
                'category_slug' => 'home-furniture-diy',
                'price' => 299.00,
                'unit' => '1 Unit',
                'description' => 'Curved bowtie accent chair upholstered in plush green boucle fabric with solid wood tapered legs.',
                'image' => 'assets/uploads/2026/01/Bedroom_1.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'damon-aged-brass-cabinet-knob',
                'name' => 'Damon Aged Brass Cabinet Knob & Pull Handle',
                'category_slug' => 'home-furniture-diy',
                'price' => 55.00,
                'unit' => '4 Pack',
                'description' => 'Artisanal aged brass hardware knobs for kitchen and bathroom cabinets with durable antiqued patina finish.',
                'image' => 'assets/uploads/2026/01/Kitchen_6.jpg',
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
                'image' => 'assets/uploads/2026/01/Office_7.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'wooden-flower-planter-box-outdoor',
                'name' => 'Handcrafted Wooden Flower Planter Box Outdoor',
                'category_slug' => 'garden-patio',
                'price' => 34.99,
                'unit' => '1 Unit',
                'description' => 'Solid cedar wood rectangular planter trough for flowers, herbs, and patio greenery with drainage holes.',
                'image' => 'assets/uploads/2026/01/Living_5.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'solar-powered-led-garden-lights-6pk',
                'name' => 'Solar Powered LED Garden Pathway Lights 6-Pack',
                'category_slug' => 'garden-patio',
                'price' => 28.50,
                'unit' => '6 Pack',
                'description' => 'Waterproof stainless steel solar stake lights for garden pathways, borders, and lawn lighting with dusk-to-dawn sensors.',
                'image' => 'assets/uploads/2026/01/Lighting_2.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'heavy-duty-garden-tool-set-5pc',
                'name' => 'Heavy Duty Ergonomic Garden Tool Set 5-Piece',
                'category_slug' => 'garden-patio',
                'price' => 24.99,
                'unit' => '5 Piece Set',
                'description' => 'Cast aluminum garden hand tools with comfortable soft rubber grips, including trowel, transplanter, cultivator, and pruner.',
                'image' => 'assets/uploads/2026/01/Living_7.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'weatherproof-patio-furniture-cover',
                'name' => 'Heavy Duty Weatherproof Patio Furniture Cover',
                'category_slug' => 'garden-patio',
                'price' => 32.00,
                'unit' => '1 Unit',
                'description' => '600D Oxford fabric waterproof cover with windproof buckle straps for outdoor table and chair sets.',
                'image' => 'assets/uploads/2026/01/Office_8.jpg',
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
                'image' => 'assets/uploads/2026/01/Office_5.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'wireless-magnetic-car-phone-mount',
                'name' => 'Wireless Fast Charging Magnetic Car Phone Mount',
                'category_slug' => 'vehicle-parts-accessories',
                'price' => 18.50,
                'unit' => '1 Unit',
                'description' => '15W fast wireless charging magnetic air vent mount with 360-degree ball rotation for smartphones.',
                'image' => 'assets/uploads/2026/01/Office_6.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'digital-tyre-pressure-gauge-lcd',
                'name' => 'Digital Tyre Pressure Gauge with Backlit LCD Display',
                'category_slug' => 'vehicle-parts-accessories',
                'price' => 12.99,
                'unit' => '1 Unit',
                'description' => 'Accurate 150 PSI digital tire gauge with lighted nozzle and non-slip grip for cars, bikes, and vans.',
                'image' => 'assets/uploads/2026/01/Office_1.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => '4-wheel-furniture-moving-tool-set',
                'name' => '4-Wheel Heavy Duty Vehicle & Furniture Moving Tool Set',
                'category_slug' => 'vehicle-parts-accessories',
                'price' => 55.00,
                'unit' => '4 Piece Set',
                'description' => 'Heavy duty transport dollies supporting up to 330 lbs each with 360-degree rotating wheels.',
                'image' => 'assets/uploads/2026/01/Office_2.jpg',
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
                'image' => 'assets/uploads/2026/01/Bedroom_9.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'resistance-exercise-bands-set',
                'name' => 'Resistance Exercise Bands Set with Door Anchor & Handles',
                'category_slug' => 'sporting-goods',
                'price' => 19.99,
                'unit' => '11 Piece Set',
                'description' => 'Stackable resistance workout bands up to 150 lbs for strength training, physical therapy, and home fitness.',
                'image' => 'assets/uploads/2026/01/Bedroom_7.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'insulated-stainless-steel-water-bottle-1l',
                'name' => 'Insulated Stainless Steel Sports Water Bottle 1 Litre',
                'category_slug' => 'sporting-goods',
                'price' => 16.50,
                'unit' => '1 Unit',
                'description' => 'Double-wall vacuum insulated flask keeping drinks cold for 24h or hot for 12h with leakproof straw lid.',
                'image' => 'assets/uploads/2026/01/Bathroom_5.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'lightweight-folding-camping-chair',
                'name' => 'Lightweight Compact Folding Camping Chair',
                'category_slug' => 'sporting-goods',
                'price' => 27.50,
                'unit' => '1 Unit',
                'description' => 'Heavy-duty aluminum portable camping chair with cup holder and compact storage bag.',
                'image' => 'assets/uploads/2026/01/Living_6.jpg',
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
                'image' => 'assets/uploads/2026/01/Bedroom_4.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'interactive-automatic-laser-cat-toy',
                'name' => 'Interactive Automatic Rotating Laser Cat Toy',
                'category_slug' => 'pet-supplies',
                'price' => 17.99,
                'unit' => '1 Unit',
                'description' => 'Hands-free automatic 360-degree laser pointer toy with random patterns and timer modes for cats.',
                'image' => 'assets/uploads/2026/01/Office_9.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'stainless-steel-double-pet-bowls',
                'name' => 'Stainless Steel No-Spill Double Pet Food & Water Bowls',
                'category_slug' => 'pet-supplies',
                'price' => 15.50,
                'unit' => '1 Set',
                'description' => 'Non-skid silicone base with two removable rust-proof stainless steel feeding dishes.',
                'image' => 'assets/uploads/2026/01/Bathroom_8.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'heavy-duty-retractable-dog-leash-5m',
                'name' => 'Heavy Duty Retractable Dog Leash 5 Metres with Brake',
                'category_slug' => 'pet-supplies',
                'price' => 13.99,
                'unit' => '1 Unit',
                'description' => 'Tangle-free 360-degree nylon ribbon lead with quick-lock single-button braking mechanism.',
                'image' => 'assets/uploads/2026/01/Bathroom_7.jpg',
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
                'image' => 'assets/uploads/2026/01/Lighting_1.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => '20000mah-fast-charging-portable-power-bank',
                'name' => '20000mAh Fast Charging Portable Power Bank PD 20W',
                'category_slug' => 'mobile-phones-communication',
                'price' => 28.00,
                'unit' => '1 Unit',
                'description' => 'High capacity ultra-compact external battery pack with dual USB-A and USB-C output ports.',
                'image' => 'assets/uploads/2026/01/Office_3.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'aluminum-adjustable-phone-tablet-stand',
                'name' => 'Universal Adjustable Aluminum Desktop Phone & Tablet Stand',
                'category_slug' => 'mobile-phones-communication',
                'price' => 14.99,
                'unit' => '1 Unit',
                'description' => 'Sturdy weighted metal desk cradle with anti-slip rubber pads for iPhone, iPad, and Android devices.',
                'image' => 'assets/uploads/2026/01/Lighting_4.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => '65w-gan-fast-wall-charger-3port',
                'name' => 'High-Speed USB-C GaN 65W Wall Charger 3-Port Fast Plug',
                'category_slug' => 'mobile-phones-communication',
                'price' => 24.99,
                'unit' => '1 Unit',
                'description' => 'Next-gen GaN fast charging brick with 2x Type-C and 1x USB-A ports for laptops, phones, and tablets.',
                'image' => 'assets/uploads/2026/01/Lighting_5.jpg',
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
                'image' => 'assets/uploads/2026/01/Bathroom_9.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'sonic-electric-toothbrush-4-heads',
                'name' => 'Sonic Electric Toothbrush with 4 Replacement Brush Heads',
                'category_slug' => 'health-beauty',
                'price' => 32.00,
                'unit' => '1 Set',
                'description' => '40,000 VPM sonic motor rechargeable toothbrush with 5 cleaning modes and 2-minute smart timer.',
                'image' => 'assets/uploads/2026/01/Bathroom_2.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'professional-hair-clipper-beard-trimmer',
                'name' => 'Professional Cordless Hair Clipper & Beard Trimmer Kit',
                'category_slug' => 'health-beauty',
                'price' => 27.50,
                'unit' => '1 Kit',
                'description' => 'Rechargeable precision hair grooming set with titanium ceramic blades and multiple guide combs.',
                'image' => 'assets/uploads/2026/01/Bathroom_5.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'ultrasonic-essential-oil-diffuser-500ml',
                'name' => 'Aromatherapy Ultrasonic Essential Oil Diffuser 500ml',
                'category_slug' => 'health-beauty',
                'price' => 22.00,
                'unit' => '1 Unit',
                'description' => 'Cool mist aroma humidifier with 7 soothing ambient LED light colours and auto shut-off function.',
                'image' => 'assets/uploads/2026/01/Lighting_6.jpg',
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
                'image' => 'assets/uploads/2026/01/Office_4.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'ergonomic-aluminum-laptop-cooling-stand',
                'name' => 'Adjustable Ergonomic Aluminum Laptop Cooling Stand',
                'category_slug' => 'computers-tablets-networking',
                'price' => 25.00,
                'unit' => '1 Unit',
                'description' => 'Foldable ventilated riser supporting up to 17-inch laptops with 6-level height adjustment.',
                'image' => 'assets/uploads/2026/01/Office_7.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => '7-in-1-dual-4k-hdmi-usbc-hub',
                'name' => '7-in-1 Dual 4K HDMI USB-C Multiport Hub Adapter',
                'category_slug' => 'computers-tablets-networking',
                'price' => 29.99,
                'unit' => '1 Unit',
                'description' => 'High speed dongle with dual 4K HDMI ports, 100W PD charging, SD/TF card reader, and USB 3.0 ports.',
                'image' => 'assets/uploads/2026/01/Office_8.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'wireless-bluetooth-keyboard-mouse-combo',
                'name' => 'Slim Rechargeable Wireless Bluetooth Keyboard & Mouse Combo',
                'category_slug' => 'computers-tablets-networking',
                'price' => 32.50,
                'unit' => '1 Set',
                'description' => 'Quiet low-profile keys with multi-device Bluetooth and 2.4GHz wireless connectivity for Mac and Windows.',
                'image' => 'assets/uploads/2026/01/Office_9.jpg',
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
                'image' => 'assets/uploads/2026/01/Living_7.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'wall-clock-unique-motifs',
                'name' => 'Decorative Wall Clock with Artisan Motifs',
                'category_slug' => 'collectables',
                'price' => 145.00,
                'unit' => '1 Unit',
                'description' => 'Handcrafted wall clock featuring painted floral motifs and rustic wrought iron frame.',
                'image' => 'assets/uploads/2026/01/Living_8.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'teo-square-cast-decorative-tray',
                'name' => 'Teo Square Cast Aluminum Decorative Tray Bronze',
                'category_slug' => 'collectables',
                'price' => 95.00,
                'unit' => '1 Unit',
                'description' => 'Solid aluminum square tray with hand-rubbed antique bronze finish for coffee tables and consoles.',
                'image' => 'assets/uploads/2026/01/Living_6.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'vintage-cast-iron-mechanical-coin-bank',
                'name' => 'Vintage Cast Iron Mechanical Coin Bank Replica',
                'category_slug' => 'collectables',
                'price' => 38.50,
                'unit' => '1 Unit',
                'description' => 'Heavy antique-style mechanical money box with moving lever action and hand-painted finish.',
                'image' => 'assets/uploads/2026/01/Living_9.jpg',
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
                'image' => 'assets/uploads/2026/01/Office_8.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'premium-acrylic-paint-set-24-colours',
                'name' => 'Premium Acrylic Paint Set 24 Vibrant Colours',
                'category_slug' => 'crafts',
                'price' => 18.50,
                'unit' => '24 Tube Set',
                'description' => 'Richly pigmented artist grade non-toxic acrylic paints for canvas, wood, fabric, and ceramic art.',
                'image' => 'assets/uploads/2026/01/Living_3.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'hand-carved-wooden-stamp-printing-blocks',
                'name' => 'Hand-Carved Wooden Stamp Printing Blocks 5-Piece',
                'category_slug' => 'crafts',
                'price' => 14.50,
                'unit' => '5 Piece Set',
                'description' => 'Traditional artisan wooden printing blocks for textile, pottery, clay, and scrapbooking designs.',
                'image' => 'assets/uploads/2026/01/Living_1.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => '100-percent-cotton-macrame-cord-3mm-200m',
                'name' => '100% Natural Cotton Macrame Cord 3mm 200 Metres',
                'category_slug' => 'crafts',
                'price' => 12.99,
                'unit' => '200m Roll',
                'description' => 'Soft 4-strand twisted unbleached natural cotton rope for plant hangers, wall hangings, and craft projects.',
                'image' => 'assets/uploads/2026/01/Bathroom_7.jpg',
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
                'image' => 'assets/uploads/2026/01/Lighting_7.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'waterproof-portable-bluetooth-speaker-bass',
                'name' => 'Waterproof Portable Bluetooth Speaker with Bass Boost',
                'category_slug' => 'sound-vision',
                'price' => 36.50,
                'unit' => '1 Unit',
                'description' => 'IPX7 waterproof outdoor wireless speaker with 360-degree surround sound and 24-hour playtime.',
                'image' => 'assets/uploads/2026/01/Lighting_3.jpg',
                'is_featured' => true,
            ],
            [
                'slug' => 'studio-condenser-usb-microphone-kit',
                'name' => 'Studio Condenser USB Microphone Kit with Boom Arm',
                'category_slug' => 'sound-vision',
                'price' => 42.00,
                'unit' => '1 Kit',
                'description' => 'Cardioid podcasting mic with shock mount, pop filter, desk clamp arm, and zero-latency monitoring.',
                'image' => 'assets/uploads/2026/01/Lighting_8.jpg',
                'is_featured' => false,
            ],
            [
                'slug' => 'mini-full-hd-1080p-home-projector',
                'name' => 'Mini Full HD 1080P Home Cinema Projector Portable',
                'category_slug' => 'sound-vision',
                'price' => 89.00,
                'unit' => '1 Unit',
                'description' => 'Compact 9000 lumens movie projector with HDMI, USB, and screen mirroring support up to 200 inches.',
                'image' => 'assets/uploads/2026/01/Lighting_2.jpg',
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
                copy($fallbackSource, $fullPath);
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
                    'is_active' => true,
                ]
            );
        }
    }
}
