<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Features\User\Seeders\FirstUserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call(ProductSeeder::class);
        $this->call(SerialNumberSeeder::class);
        $this->call(UserTypeSeeder::class);
        $this->call(FirstUserSeeder::class);
    }
}
