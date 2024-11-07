<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTypeFactory extends Factory
{
    protected $model = UserType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
