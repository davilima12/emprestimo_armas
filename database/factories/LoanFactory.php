<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition()
    {
        return [
            'user_giver_id' => User::factory(),
            'user_receiver_id' => User::factory(),
            'user_receipt_id' => User::factory(),
            'receipt_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
