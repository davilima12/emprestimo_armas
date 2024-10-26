<?php

declare(strict_types=1);

namespace App\Features\Auth\Models;

use App\Features\Auth\Enums\Permissions;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property int $id
 */
class Permission extends Model
{
    protected $fillable = [
        'id',
        'name',
    ];

    public function getEnum(): Permissions
    {
        return Permissions::fromId($this->id);
    }
}
