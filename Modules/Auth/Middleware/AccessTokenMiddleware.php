<?php

namespace Modules\Auth\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;
use Modules\Auth\Facades\AuthService;

final class AccessTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        $accessToken = $request->header('Authorization') ?? '';

        $authService = app(AuthService::class);
        $isAccessTokenValid = $authService->validateAccessToken($accessToken);
        if ($isAccessTokenValid) {
            return $next($request);
        }

        return Response::json(['error' => 'Access denied'], 403);
    }
}
