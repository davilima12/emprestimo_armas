<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\Product;
use App\Models\ProductSerial;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanProductFactory extends Factory
{
    protected $model = LoanProduct::class;

    public function definition()
    {
        return [
            'loan_id' => Loan::factory(),
            'product_id' => Product::factory(),
            'product_serial_id' => ProductSerial::factory(),
            'magazines' => $this->faker->numberBetween(1, 5),
            'ammunition' => $this->faker->numberBetween(10, 100),
        ];
    }
}
