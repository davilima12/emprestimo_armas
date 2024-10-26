<?php

declare(strict_types=1);

namespace App\Features\Auth\Models;

use App\Features\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property string $token
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @method static PasswordResetToken create(array $array)
 * @method static PasswordResetToken|null findByToken(string $token)
 */
class PasswordResetToken extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'email',
        'token',
    ];

    protected function casts(): array
    {
        return [
            'token' => 'string',
        ];
    }

    public function isValid(): bool
    {
        return $this->created_at->diffInMinutes() < 60;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
