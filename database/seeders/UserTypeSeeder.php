<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Admin'],
            ['name' => 'User'],
            ['name' => 'Middle_room'],
        ];

        foreach ($data as $d) {
            UserType::updateOrCreate(
                [
                    'name' =>   $d['name'],
                ],
                $d
            );
        }
    }
}
