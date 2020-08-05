<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Modules\Auth\Facades\AuthService;
use Modules\Auth\Http\FormRequests\LoginRequest;

final class AuthController
{
    public function logUserIn(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $newAccessToken = $authService->login($request->username, $request->password);
        if (is_null($newAccessToken)) {
            $errorResponse = [
                'error' => 'Could not generate an access token, try again later',
            ];

            return Response::json($errorResponse, 400);
        }

        return Response::json($newAccessToken, 201);
    }

    public function logUserOut(): JsonResponse {
        $logoutResponse = [
            'message' => 'User logged out',
        ];

        return Response::json($logoutResponse, 200);
    }
}
