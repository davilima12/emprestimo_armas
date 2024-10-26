<?php

declare(strict_types=1);

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Services\RoleService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class RolesController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = app(RoleService::class)->all();

        return response()->json($roles->toArray());
    }
}
