<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserType;
use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\Auth\Models\AuthToken;
use App\Features\Auth\Singletons\AuthenticatedUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null($request->bearerToken())) {
            throw UnauthorizedException::unauthorized();
        }

        $authToken = AuthToken::findByToken($request->bearerToken());
        $authToken->validateOrCry();
        AuthenticatedUser::set($authToken->user);
        $user = $authToken->user;

        if (!$user || $user->user_type_id !== UserType::Admin->value) {
            return response()->json(['message' => 'Only admin can perform this action'], 401);
        }

        return $next($request);
    }
}
