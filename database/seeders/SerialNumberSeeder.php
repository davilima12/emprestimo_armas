<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductSerial;

class SerialNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serialNumbers = [
            [
                'product_id' => Product::where('name', 'AK-47')->first()->id,
                'serial_number' => 'AK47-001',
            ],
            [
                'product_id' => Product::where('name', 'Bulletproof Vest')->first()->id,
                'serial_number' => 'VEST-002',
            ],
            [
                'product_id' => Product::where('name', 'Glock 19')->first()->id,
                'serial_number' => 'GLOCK-003',
            ],
        ];

        foreach ($serialNumbers as $serial) {
            ProductSerial::updateOrCreate(
                [
                    'product_id'    => $serial['product_id'],
                    'serial_number' => $serial['serial_number']
                ],
                $serial
            );
        }
    }
}
