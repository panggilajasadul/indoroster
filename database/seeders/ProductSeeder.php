<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $category = Category::updateOrCreate(
            ['slug' => 'roster-beton'],
            [
                'name' => 'Roster Beton',
                'description' => 'Koleksi roster beton minimalis dengan berbagai motif modern.',
                'image_url' => 'https://indoroster.com/wp-content/uploads/2025/12/decorative-vntilation-block-roster-minimalis-motif-petir.png',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $products = [
            [
                'name' => 'Roster Arrow',
                'sku' => 'IR-001',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/decorative-brizze-block-roster-minimalis-motif-lb4-2-muka-plered-purwakarta.png',
            ],
            [
                'name' => 'Roster LB4 Two Side',
                'sku' => 'IR-002',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/decorative-brizze-block-roster-minimalis-motif-lb4-2-muka-plered-purwakarta.png',
            ],
            [
                'name' => 'Roster Donat',
                'sku' => 'IR-003',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/16-768x768.webp',
            ],
            [
                'name' => 'Roster Petir',
                'sku' => 'IR-004',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/decorative-vntilation-block-roster-minimalis-motif-petir.png',
            ],
            [
                'name' => 'Roster High Nako',
                'sku' => 'IR-005',
                'price' => 18000,
                'dimensions' => '25 x 15 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/47.jpg',
            ],
            [
                'name' => 'MMC Roster',
                'sku' => 'IR-006',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/2-1.jpg',
            ],
            [
                'name' => 'Nako Sipit',
                'sku' => 'IR-007',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/97.jpg',
            ],
            [
                'name' => 'Roster L Two side',
                'sku' => 'IR-009',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg',
            ],
            [
                'name' => 'Roster L One Side',
                'sku' => 'IR-010',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/16-768x768.webp',
            ],
            [
                'name' => 'Roster Kincir lubang 2',
                'sku' => 'IR-011',
                'price' => 15000,
                'dimensions' => '20 x 20 x 10 cm',
                'weight' => 3,
                'image' => 'https://indoroster.com/wp-content/uploads/2025/12/decorative-vntilation-block-roster-minimalis-motif-petir.png',
            ],
        ];

        foreach ($products as $p) {
            $product = Product::updateOrCreate(
                ['sku' => $p['sku']],
                [
                    'category_id' => $category->id,
                    'name' => $p['name'],
                    'slug' => Str::slug($p['name']),
                    'description' => "Roster beton minimalis motif {$p['name']} dengan kualitas premium. Cocok untuk pagar, fasad rumah, dan interior.",
                    'price' => $p['price'],
                    'stock' => 1000,
                    'stock_status' => 'in_stock',
                    'dimensions' => $p['dimensions'],
                    'weight' => $p['weight'],
                    'material' => 'Beton High-Grade',
                    'is_active' => true,
                ]
            );

            // Add primary image to media table if it doesn't exist
            $product->media()->updateOrCreate(
                ['media_url' => $p['image']],
                [
                    'media_type' => 'image',
                    'is_primary' => true,
                    'sort_order' => 1,
                ]
            );
        }
    }
}
