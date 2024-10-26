<?php

declare(strict_types=1);

namespace App\Features\User\Models;

use App\Features\Auth\Enums\Permissions;
use App\Features\Auth\Enums\Roles;
use App\Features\Auth\Exceptions\BadPermissionException;
use App\Features\Auth\Exceptions\UserNotFoundException;
use App\Features\Auth\Models\AuthToken;
use App\Features\Auth\Models\PasswordResetToken;
use App\Features\Auth\Models\Permission;
use App\Features\Auth\Models\Role;
use App\Features\User\ValueObjects\Email;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Symfony\Component\Uid\Ulid;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $token
 * @property Collection<PasswordResetToken> $passwordResetTokens
 * @property Collection<Permissions> $permissions
 * @property Role $role
 */

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * @var Collection<Permission>|null
     */
    private ?Collection $permissions = null;

    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'role_id',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @throws UserNotFoundException
     */
    public static function findByEmail(Email $email, bool $throw = true): ?self
    {
        /** @var User $user */
        $user = self::query()->where('email', $email->value)->first();
        if (is_null($user) && $throw) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public static function findByToken(string $token, bool $throw = true): ?self
    {
        /** @var User $user */
        $user = self::query()->where('token', $token)->first();
        if (is_null($user) && $throw) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function getEmail(): Email
    {
        return new Email($this->email);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isVerified(): bool
    {
        return isset($this->email_verified_at) && isset($this->password);
    }

    public function passwordResetTokens(): HasMany
    {
        return $this->hasMany(PasswordResetToken::class);
    }

    public function createPasswordResetToken(): PasswordResetToken
    {
        return $this->passwordResetTokens()->create([
            'email' => $this->email,
            'token' => Ulid::generate(),
        ]);
    }

    public function generateBearerToken(): AuthToken
    {
        return AuthToken::query()->create([
            'user_id' => $this->id,
            'token' => $this->token.'.'.bcrypt(Carbon::now()->timestamp),
        ]);
    }

    public function hasPermission(Permissions $permission): bool
    {
        return $this->permissions()
            ->where('id', $permission->id())
            ->count() > 0;
    }

    /**
     * @param  Permissions[]  $permissions
     */
    public function hasAtLeastOnePermission(array $permissions): bool
    {
        return $this->permissions()
            ->whereIn('id', array_map(fn (Permissions $permission) => $permission->id(), $permissions))
            ->count() > 0;
    }

    /**
     * @param  Permissions[]  $permissions
     *
     * @throws BadPermissionException
     */
    public function validateAtLeastOnePermissionOrDie(array $permissions): void
    {
        if (!$this->hasAtLeastOnePermission($permissions)) {
            throw new BadPermissionException();
        }
    }

    /**
     * @throws BadPermissionException
     */
    public function validatePermissionOrDie(Permissions $permission): void
    {
        if (!$this->hasPermission($permission)) {
            throw new BadPermissionException();
        }
    }

    public function getRole(): Roles
    {
        return Roles::parseById($this->role_id);
    }

    public function emailHasChanged(Email $email): bool
    {
        return $this->email !== $email->value;
    }

    /**
     * @throws UserNotFoundException
     */
    public static function emailExists(Email $email): bool
    {
        return !is_null(self::findByEmail($email, throw: false));
    }

    public function permissions(): Collection
    {
        return $this->role->permissions;
    }
}
