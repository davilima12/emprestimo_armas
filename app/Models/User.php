<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'role_id',
        'email_verified_at',
        'user_type_id',
        'description',
        'private',
    ];

    public function passwordMatch($password): bool
    {
        return password_verify($password, $this->password);
    }

    public static function checkUser($email, $paswword): self
    {
        $user = self::where('email', $email)->first();

        if ($user && $user->passwordMatch($paswword)) {
            return $user;
        }

        throw new \Exception('Unauthorized');
    }
}
