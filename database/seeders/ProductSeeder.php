<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'AK-47',
                'description' => 'Assault rifle',
                'product_type' => 'weapon',
            ],
            [
                'name' => 'Bulletproof Vest',
                'description' => 'Standard body armor',
                'product_type' => 'body_armor',
            ],
            [
                'name' => 'Glock 19',
                'description' => 'Handheld pistol',
                'product_type' => 'handheld_weapon',
            ],
            [
                'name' => 'Cone',
                'description' => 'Cone amarelo',
                'product_type' => 'accessory',
            ],

            [
                'name' => 'Porta arma',
                'description' => 'Porta arma',
                'product_type' => 'accessory',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                [
                    'name' =>  $product['name'],
                    'description' => $product['description'],
                    'product_type' =>   $product['product_type'],
                ],
                $product
            );
        }
    }
}
