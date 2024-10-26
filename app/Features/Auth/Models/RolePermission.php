<?php

declare(strict_types=1);

namespace App\Features\Auth\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $role_id
 * @property int $permission_id
 * @property Collection<Permission> $permissions
 */
class RolePermission extends Model
{
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
