<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
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


    public function user_profile_picture()
    {
        return $this->hasOne(UserPhoto::class, 'user_id')->where('is_profile_picture', 1);
    }

    /**
     * Usuários que este usuário está seguindo.
     *
     * @return BelongsToMany
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id');
    }

    /**
     * Seguidores deste usuário.
     *
     * @return BelongsToMany
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id');
    }
}
