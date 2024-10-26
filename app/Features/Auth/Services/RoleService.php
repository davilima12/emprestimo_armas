<?php

declare(strict_types=1);

namespace App\Features\Auth\Services;

use App\Features\Auth\Enums\Permissions;
use App\Features\Auth\Enums\Roles;
use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\Auth\Models\Role;
use App\Features\Auth\Singletons\AuthenticatedUser;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    /**
     * @throws UnauthorizedException
     */
    public function all(): Collection
    {
        if (AuthenticatedUser::get()->hasPermission(Permissions::CREATE_ADMIN)) {
            return Role::all();
        }

        return Role::query()->whereNotIn('id', [Roles::SUPER_ADMIN->id(), Roles::ADMIN->id()])->get();
    }
}
