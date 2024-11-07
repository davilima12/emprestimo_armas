<?php

declare(strict_types=1);

namespace App\Features\User\UseCases;

use App\Features\Auth\Exceptions\InvalidActionException;
use App\Features\Auth\Exceptions\UserNotFoundException;
use App\Features\User\ValueObjects\Email;

class LoginUseCase
{
    /**
     * @throws InvalidActionException
     * @throws UserNotFoundException
     */
    public function execute(Email $email, string $password): string
    {
    }
}
