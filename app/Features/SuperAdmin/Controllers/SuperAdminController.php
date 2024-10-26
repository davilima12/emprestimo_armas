<?php

declare(strict_types=1);

namespace App\Features\SuperAdmin\Controllers;

use App\Features\Auth\Enums\Roles;
use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\Auth\Singletons\AuthenticatedUser;
use App\Features\Medias\Models\Media;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class SuperAdminController extends Controller
{
    /**
     * @throws UnauthorizedException
     */
    public function afterDeploy(): void
    {
        if (AuthenticatedUser::get()->role_id !== Roles::SUPER_ADMIN->id()) {
            throw new UnauthorizedException('Operação não permitida.');
        }
        Artisan::call('migrate --force');
        Artisan::call('db:seed --force');
    }

    public function fixMediaUrl(): void
    {
        Media::all()->each(function (Media $media) {
            return $media->update([
                'url' => str_replace('westsideapi.wardtecnology.com', 'api.westsidemotorcycle.com.br', $media->url),
            ]);
        });
    }
}
