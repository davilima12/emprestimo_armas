<?php

declare(strict_types=1);

namespace App\Features\User\Services;

use App\Features\Auth\Enums\Permissions;
use App\Features\Auth\Enums\Roles;
use App\Features\Auth\Exceptions\BadPermissionException;
use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\Auth\Exceptions\UserNotFoundException;
use App\Features\Auth\Singletons\AuthenticatedUser;
use App\Features\User\Dtos\CreateUserDto;
use App\Features\User\Dtos\UpdateUserDto;
use App\Features\User\Exceptions\UserAlreadyExistsException;
use App\Features\User\Models\User;
use App\Features\User\UseCases\ListUsersUseCase;
use Illuminate\Support\Collection;
use Symfony\Component\Uid\Ulid;

class UserService
{
    /**
     * @throws UserAlreadyExistsException
     * @throws UnauthorizedException|BadPermissionException
     */
    public function createUser(CreateUserDto $userDto): User
    {
        $this->validatePermissions($userDto->role);

        if (User::where('email', $userDto->email->value)->exists()) {
            throw new UserAlreadyExistsException();
        }

        return User::create([
            'email' => $userDto->email->value,
            'name' => $userDto->name,
            'role_id' => $userDto->role->id(),
            'token' => Ulid::generate(),
        ]);
    }

    /**
     * @return Collection<User>
     *
     * @throws UnauthorizedException|BadPermissionException
     */
    public function getUsers(?string $search = null): Collection
    {
        return app(ListUsersUseCase::class)->execute($search);
    }

    /**
     * @throws UnauthorizedException
     * @throws BadPermissionException
     * @throws UserNotFoundException
     * @throws UserAlreadyExistsException
     */
    public function updateUser(UpdateUserDto $dto): User
    {
        $this->validatePermissions($dto->role);
        $user = User::findByToken($dto->token, false);
        if (is_null($user)) {
            throw new UserNotFoundException('Usuario não encontrado!', 404);
        }
        if ($user->emailHasChanged($dto->email) && User::emailExists($dto->email)) {
            throw new UserAlreadyExistsException();
        }

        $user->update($dto->toModelArray());

        return $user;
    }

    /**
     * @throws BadPermissionException
     * @throws UnauthorizedException
     * @throws UserNotFoundException
     */
    public function delete(string $token): void
    {
        try {
            $user = User::findByToken($token);
            $this->validatePermissions($user->getRole());
            $user->delete();
        } catch (UserNotFoundException) {
            throw new UserNotFoundException('Usuario não encontrado!', 404);
        }
    }

    /**
     * @throws BadPermissionException
     * @throws UnauthorizedException
     */
    public function validatePermissions(Roles $role): void
    {
        AuthenticatedUser::get()->validatePermissionOrDie(Permissions::CREATE_USER);
        if (in_array($role->id(), [Roles::SUPER_ADMIN->id(), Roles::ADMIN->id()])) {
            AuthenticatedUser::get()->validatePermissionOrDie(Permissions::CREATE_ADMIN);
        }
    }
}
