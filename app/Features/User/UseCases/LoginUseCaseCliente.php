<?php

declare(strict_types=1);

namespace App\Features\User\UseCases;

use App\Features\Auth\Exceptions\InvalidActionException;
use App\Features\Auth\Exceptions\UserNotFoundException;
use App\Features\User\Models\User;
use App\Features\User\ValueObjects\Email;
use Illuminate\Support\Facades\Hash;

class LoginUseCaseCliente
{
    /**
     * @throws InvalidActionException
     * @throws UserNotFoundException
     */
    public function execute(Email $email, string $password): string
    {
        $user = User::where('email', $email->value)->first();
        if (is_null($user)) {
            throw new UserNotFoundException('Email ou senha incorretos!');
        }

        // if (!$user->isVerified()) {
        //     throw new InvalidActionException('Ação invalida, por favor confirme seu email!');
        // }

        $passwordMatch = Hash::check($password, $user->password);
        if (!$passwordMatch) {
            throw new UserNotFoundException('Email ou senha incorretos!');
        }

        return $user->generateBearerToken()->token;
    }
}
