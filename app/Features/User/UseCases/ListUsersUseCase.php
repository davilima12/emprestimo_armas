<?php

declare(strict_types=1);

namespace App\Features\User\UseCases;

use App\Features\Auth\Enums\Permissions;
use App\Features\Auth\Enums\Roles;
use App\Features\Auth\Exceptions\BadPermissionException;
use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\Auth\Singletons\AuthenticatedUser;
use App\Features\User\Models\User;
use App\Models\User as ModelsUser;
use Illuminate\Support\Collection;

class ListUsersUseCase
{
    /**
     * @throws UnauthorizedException
     * @throws BadPermissionException
     */
    public function execute(?string $search = null): Collection
    {
        return ModelsUser::query()
            ->where('email','like', "%$search%")
            ->orWhere('name','like', "%$search%")
            ->get();
    }

    /**
     * @throws UnauthorizedException
     */
    public function showAllUsers(): Collection
    {
        if (!AuthenticatedUser::get()->hasPermission(Permissions::LIST_USERS)) {
            return collect();
        }
        if (!AuthenticatedUser::get()->hasPermission(Permissions::CREATE_ADMIN)) {
            return User::query()->whereNotIn('role_id', [Roles::SUPER_ADMIN->id()])->get();
        }

        return User::all();
    }
}
