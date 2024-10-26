<?php

declare(strict_types=1);

namespace App\Features\Auth\Models;

use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 */
class AuthToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        $dateExpired = $this->created_at->addMinutes((int) config('auth.minutes_to_expire_password'));

        return $dateExpired->isFuture();
    }

    /**
     * @throws UnauthorizedException
     */
    public function validateOrCry(): void
    {
        if (!$this->isValid()) {
            throw new UnauthorizedException('Usuario não autorizado!');
        }
    }

    /**
     * @throws UnauthorizedException
     */
    public static function findByToken(string $token, bool $throw = true): ?self
    {
        /** @var AuthToken $token */
        $token = self::query()->where('token', $token)->first();
        if (is_null($token) && $throw) {
            throw UnauthorizedException::unauthorized();
        }

        return $token;
    }
}
