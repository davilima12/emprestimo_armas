<?php

declare(strict_types=1);

namespace App\Features\User\Dtos;

use App\Features\Auth\Enums\Roles;
use App\Features\User\Requests\CreateUserRequest;
use App\Features\User\ValueObjects\Email;

class CreateUserDto
{
    public function __construct(
        public string $name,
        public Email $email,
        public Roles $role,
    ) {
    }

    public static function parseFromRequest(CreateUserRequest $request): self
    {
        return new self(
            name: $request->name,
            email: new Email($request->email),
            role: Roles::parseOrFail($request->role),
        );
    }
}
