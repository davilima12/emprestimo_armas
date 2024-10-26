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
        if (app()->environment() === 'testing') {
            return;
        }

        $userExist = User::findByEmail(new Email(config('auth.first_user.email')), throw: false);
        if ($userExist) {
            return;
        }

        User::create([
            'email' => 'davidaer8847@gmail.com',
            'name' => 'admin',
            'role_id' => Roles::SUPER_ADMIN->id(),
            'token' => Ulid::generate(),
            'password' => bcrypt('admin'),
            'user_type_id' => 2
        ]);
    }
}
