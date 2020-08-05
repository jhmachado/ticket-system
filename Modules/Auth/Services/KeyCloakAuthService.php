<?php

namespace Modules\Auth\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Facades\AuthService;
use Modules\Auth\Models\AccessToken;

final class KeyCloakAuthService implements AuthService
{
    public function login(string $username, string $password): ?AccessToken
    {
        $uri = self::createUri();
        $payload = self::composePayload($username, $password);
        $response = Http::asForm()->post($uri, $payload);

        if ($response->ok()) {
            return self::parseResponse($response);
        }

        $logMessage = "Error while trying to login with the payload: {$username} and {$password}";
        Log::alert($logMessage);

        return null;
    }

    private static function createUri(): string {
        $keycloakHost = config('keycloak.host');
        return "{$keycloakHost}auth/realms/ticket-system/protocol/openid-connect/token";
    }

    private static function composePayload(string $username, string $password): array {
        return [
            'client_id' => config('keycloak.client_id'),
            'client_secret' => config('keycloak.secret'),
            'grant_type' => config('keycloak.grant_type'),
            'scope' => config('keycloak.scope'),
            'username' => $username,
            'password' => $password,
        ];
    }

    private static function parseResponse(Response $response): AccessToken {
        return new AccessToken([
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
        ]);
    }
}
