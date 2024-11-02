<?php

declare(strict_types=1);

namespace App\Features\User\Seeders;

use App\Features\Auth\Enums\Roles;
use App\Features\Auth\Exceptions\UserNotFoundException;
use App\Features\User\Exceptions\UserAlreadyExistsException;
use App\Features\User\Models\User;
use App\Features\User\ValueObjects\Email;
use Illuminate\Database\Seeder;
use Symfony\Component\Uid\Ulid;

class FirstUserSeeder extends Seeder
{
    /**
     * @throws UserAlreadyExistsException
     * @throws UserNotFoundException
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'teste@gmail.com',
        ],
        [
            'email' => 'teste@gmail.com',
            'name' => 'teste',
            'role_id' => Roles::SUPER_ADMIN->id(),
            'token' => Ulid::generate(),
            'password' => bcrypt('teste'),
            'user_type_id' => 1
        ]);


        User::updateOrCreate([
            'email' => 'teste2@gmail.com',
        ],
        [
            'email' => 'teste2@gmail.com',
            'name' => 'teste2',
            'role_id' => Roles::SUPER_ADMIN->id(),
            'token' => Ulid::generate(),
            'password' => bcrypt('teste'),
            'user_type_id' => 2
        ]);

        User::updateOrCreate([
            'email' => 'teste3@gmail.com',
        ],
        [
            'email' => 'teste3@gmail.com',
            'name' => 'teste3',
            'role_id' => Roles::SUPER_ADMIN->id(),
            'token' => Ulid::generate(),
            'password' => bcrypt('teste'),
            'user_type_id' => 2
        ]);
    }
}
