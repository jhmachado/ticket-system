<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Modules\Auth\Http\FormRequests\LoginRequest;

final class AuthController
{
    public function logUserIn(LoginRequest $request): JsonResponse
    {
        $newAccessToken = [
            'access_token' => '123456',
            'expires_in' => 600,
        ];

        return Response::json($newAccessToken, 201);
    }

    public function logUserOut(): JsonResponse {
        $logoutResponse = [
            'message' => 'User logged out',
        ];

        return Response::json($logoutResponse, 200);
    }
}
