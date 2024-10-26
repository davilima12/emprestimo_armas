<?php

declare(strict_types=1);

namespace App\Features\Auth\Singletons;

use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\User\Models\User;

class AuthenticatedUser
{
    private static ?User $user = null;

    /**
     * @throws UnauthorizedException
     */
    public static function get(): User
    {
        if (is_null(self::$user)) {
            throw UnauthorizedException::unauthorized();
        }

        return self::$user;
    }

    public static function set(?User $user): void
    {
        self::$user = $user;
    }
}
