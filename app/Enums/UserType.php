<?php

declare(strict_types=1);

namespace App\Enums;

enum UserType: int
{
    case Admin = 1;
    case User = 2;

    /**
     * Get the name of the user type by ID.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            UserType::Admin => 'Admin',
            UserType::User => 'User',
        };
    }
}
