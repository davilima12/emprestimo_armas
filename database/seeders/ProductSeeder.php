<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

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
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
