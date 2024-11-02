<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Features\User\Seeders\FirstUserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(FirstUserSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SerialNumberSeeder::class);
        $this->call(UserTypeSeeder::class);

    }
}
