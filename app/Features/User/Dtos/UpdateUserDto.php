<?php

declare(strict_types=1);

namespace App\Features\User\Dtos;

use App\Features\Auth\Enums\Roles;
use App\Features\User\Requests\UpdateUserRequest;
use App\Features\User\ValueObjects\Email;

class UpdateUserDto
{
    public function __construct(
        public string $token,
        public string $name,
        public Roles $role,
        public Email $email,
        public ?string $password = null,
    ) {
    }

    public static function parseFromRequest(UpdateUserRequest $request): self
    {
        return new self(
            token: $request->get('token'),
            name: $request->get('name'),
            role: Roles::parseOrFail($request->get('role')),
            email: new Email($request->get('email')),
            password: $request->get('password'),
        );
    }

    public function toModelArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'password' => $this->password,
            'role_id' => $this->role->id(),
            'email' => $this->email->value,
        ]);
    }
}
