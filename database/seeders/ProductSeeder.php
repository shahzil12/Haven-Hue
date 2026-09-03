<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create an admin/seller user for products
        $seller = User::where('role', 'admin')->first() 
            ?? User::firstOrCreate(
                ['email' => 'seller@havenhue.com'],
                [
                    'name' => 'HavenHue Artisan Store',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                ]
            );

        $productsData = [
            // Table Decor & Accents
            [
                'category_name' => 'Table Decor & Accents',
                'name' => 'Artisan Teak Sculptural Bowl',
                'description' => 'Hand-carved from solid reclaimed teak wood, this decorative bowl highlights organic grain patterns and rich natural tones. Perfect as a dining table centerpiece or foyer accent.',
                'price' => 2499.00,
                'material_type' => 'Solid Reclaimed Teak Wood',
                'dimensions' => '30cm x 30cm x 12cm',
                'stock' => 15,
                'images' => [
                    'https://images.unsplash.com/photo-1615873968403-89e068629265?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'category_name' => 'Table Decor & Accents',
                'name' => 'Nordic Brass & Walnut Candle Holders',
                'description' => 'Set of 3 elegant tiered candle stands combining warm American walnut wood with brushed brass accents. Creates a warm ambient cozy centerpiece for dining tables.',
                'price' => 1899.00,
                'material_type' => 'Walnut Wood & Brushed Brass',
                'dimensions' => 'Heights 15cm, 20cm, 25cm',
                'stock' => 20,
                'images' => [
                    'https://images.unsplash.com/photo-1603006905003-be475563bc59?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'category_name' => 'Table Decor & Accents',
                'name' => 'Minimalist Geometric Wooden Desk Clock',
                'description' => 'A sleek hexagonal desktop timepiece carved from sustainable mango wood with silent quartz movement and brushed gold hands.',
                'price' => 1299.00,
                'material_type' => 'Sustainable Mango Wood',
                'dimensions' => '18cm x 16cm x 5cm',
                'stock' => 25,
                'images' => [
                    'https://images.unsplash.com/photo-1563861826100-9cb868fdbe1c?auto=format&fit=crop&q=80&w=800'
                ]
            ],

            // Wall Art & Mirrors
            [
                'category_name' => 'Wall Art & Mirrors',
                'name' => 'Boho Hand-Carved Mandala Wooden Wall Plaque',
                'description' => 'Intricately detailed circular wall art carved by master artisans. Distressed white and natural wood finish adds warmth and textured depth to any living room wall.',
                'price' => 3499.00,
                'material_type' => 'Solid Mango Wood & Carved MDF',
                'dimensions' => '60cm Diameter x 3cm Depth',
                'stock' => 10,
                'images' => [
                    'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'category_name' => 'Wall Art & Mirrors',
                'name' => 'Arch Geometric Oak Framed Wall Mirror',
                'description' => 'Contemporary arched accent mirror featuring a minimalist solid white oak frame. Reflects natural light to brighten hallways, dressers, or entryways.',
                'price' => 4999.00,
                'material_type' => 'White Oak & HD Glass Mirror',
                'dimensions' => '80cm x 50cm x 4cm',
                'stock' => 8,
                'images' => [
                    'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&q=80&w=800'
                ]
            ],

            // Kitchen & Diningware
            [
                'category_name' => 'Kitchen & Diningware',
                'name' => 'Rustic Acacia Wood Serving Board & Knife Set',
                'description' => 'Heavyweight acacia wood charcuterie board featuring natural live edges and integrated stainless steel cheese knives with matching wooden handles.',
                'price' => 2199.00,
                'material_type' => 'Natural Acacia Wood & Stainless Steel',
                'dimensions' => '45cm x 22cm x 2.5cm',
                'stock' => 30,
                'images' => [
                    'https://images.unsplash.com/photo-1590794056226-79ef3a8147e1?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'category_name' => 'Kitchen & Diningware',
                'name' => 'Handcrafted Olive Wood Salad Bowl & Servers',
                'description' => 'Smoothly turned olive wood salad bowl with distinct swirling grain patterns. Includes matching pair of handcrafted serving spoons.',
                'price' => 2999.00,
                'material_type' => 'Mediterranean Olive Wood',
                'dimensions' => '28cm Diameter x 10cm Height',
                'stock' => 12,
                'images' => [
                    'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&q=80&w=800'
                ]
            ],

            // Lighting & Lanterns
            [
                'category_name' => 'Lighting & Lanterns',
                'name' => 'Zen Wooden Slat Pendant Light',
                'description' => 'Sculptural pendant lamp made from curved birch plywood slats that cast soft, mesmerizing ambient light shadows across your living room or dining nook.',
                'price' => 3799.00,
                'material_type' => 'Natural Birch Plywood & E27 Fitting',
                'dimensions' => '40cm Diameter x 35cm Height',
                'stock' => 14,
                'images' => [
                    'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'category_name' => 'Lighting & Lanterns',
                'name' => 'Vintage Sheesham Wood Table Lamp',
                'description' => 'Classic turned sheesham wood lamp base paired with a natural linen drum shade. Warm illumination perfect for bedside tables or reading nooks.',
                'price' => 2699.00,
                'material_type' => 'Sheesham Wood & Linen Shade',
                'dimensions' => '25cm x 25cm x 52cm',
                'stock' => 18,
                'images' => [
                    'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?auto=format&fit=crop&q=80&w=800'
                ]
            ],

            // Planters & Vases
            [
                'category_name' => 'Planters & Vases',
                'name' => 'Mid-Century Modern Elevated Wooden Plant Stand',
                'description' => 'Tapered solid walnut plant stand designed to elevate your favorite indoor leafy greenery. Holds plant pots up to 10 inches in diameter.',
                'price' => 1599.00,
                'material_type' => 'Solid American Walnut',
                'dimensions' => '30cm x 30cm x 40cm',
                'stock' => 22,
                'images' => [
                    'https://images.unsplash.com/photo-1485955900006-10f4d324d411?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'category_name' => 'Planters & Vases',
                'name' => 'Artisan Terracotta & Timber Floor Vase',
                'description' => 'Tall earthy terracotta vase nestled on a three-legged ash wood base. Ideal for displaying tall dried pampas grass or faux eucalyptus stems.',
                'price' => 3299.00,
                'material_type' => 'Terracotta Clay & Ash Wood',
                'dimensions' => '24cm Diameter x 65cm Height',
                'stock' => 9,
                'images' => [
                    'https://images.unsplash.com/photo-1612196808214-b7e239e5f6b7?auto=format&fit=crop&q=80&w=800'
                ]
            ],

            // Luxury Accent Furniture
            [
                'category_name' => 'Luxury Accent Furniture',
                'name' => 'Minimalist Floating Oak Wall Shelves (Set of 2)',
                'description' => 'Heavy-duty solid oak floating shelves with hidden steel mounting brackets. Sleek design for displaying books, photo frames, and small plants.',
                'price' => 2299.00,
                'material_type' => 'Solid European Oak',
                'dimensions' => '60cm x 20cm x 4cm each',
                'stock' => 16,
                'images' => [
                    'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'category_name' => 'Luxury Accent Furniture',
                'name' => 'Carved Wooden Stool & Side Table',
                'description' => 'Versatile accent piece crafted from a single trunk block of cedar wood. Functions beautifully as an end table, bedside stand, or extra seating.',
                'price' => 5499.00,
                'material_type' => 'Solid Cedar Wood',
                'dimensions' => '35cm x 35cm x 45cm',
                'stock' => 6,
                'images' => [
                    'https://images.unsplash.com/photo-1532372670776-806161270595?auto=format&fit=crop&q=80&w=800'
                ]
            ],
        ];

        foreach ($productsData as $data) {
            $category = Category::where('name', $data['category_name'])->first();

            if (!$category) {
                continue;
            }

            $product = Product::updateOrCreate(
                [
                    'name' => $data['name'],
                    'category_id' => $category->id,
                ],
                [
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'material_type' => $data['material_type'],
                    'dimensions' => $data['dimensions'],
                    'stock' => $data['stock'],
                    'seller_id' => $seller->id,
                ]
            );

            // Add images if not already attached
            foreach ($data['images'] as $index => $imageUrl) {
                ProductImage::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'image_path' => $imageUrl,
                    ],
                    [
                        'is_primary' => ($index === 0),
                    ]
                );
            }
        }
    }
}
