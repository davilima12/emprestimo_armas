<?php

declare(strict_types=1);

namespace App\Features\Auth\Middleware;

use App\Features\Auth\Exceptions\UnauthorizedException;
use App\Features\Auth\Models\AuthToken;
use App\Features\Auth\Singletons\AuthenticatedUser;
use Closure;
use Illuminate\Http\Request;

class AuthenticateMiddleware
{
    /**
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_null($request->bearerToken())) {
            throw UnauthorizedException::unauthorized();
        }
        $authToken = AuthToken::findByToken($request->bearerToken());
        $authToken->validateOrCry();
        AuthenticatedUser::set($authToken->user);

        return $next($request);
    }
}
