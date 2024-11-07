<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductSerial;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSerialFactory extends Factory
{
    protected $model = ProductSerial::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'serial_number' => $this->faker->uuid(),
            'is_loaned' => $this->faker->boolean(),
        ];
    }
}
