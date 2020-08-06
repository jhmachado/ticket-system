<?php

namespace Modules\Auth\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Facades\AuthService;
use Modules\Auth\Models\AccessToken;

final class KeyCloakAuthService implements AuthService
{
    private const KEY_CLOAK_CREATE_TOKEN_ENDPOINT = 'auth/realms/ticket-system/protocol/openid-connect/token';

    private const KEY_CLOAK_VALIDATE_TOKEN_ENDPOINT = 'auth/realms/ticket-system/protocol/openid-connect/token/introspect';

    public function login(string $username, string $password): ?AccessToken
    {
        $uri = self::createUri(self::KEY_CLOAK_CREATE_TOKEN_ENDPOINT);
        $payload = self::composePayload($username, $password);
        $response = Http::asForm()->post($uri, $payload);

        if ($response->ok()) {
            return self::parseResponse($response);
        }

        $logMessage = "Error while trying to login with the payload: {$username} and {$password}";
        Log::alert($logMessage);

        return null;
    }

    public function validateAccessToken(string $accessToken): bool
    {
        $uri = self::createUri(self::KEY_CLOAK_VALIDATE_TOKEN_ENDPOINT);
        $payload = [
            'token' => $accessToken,
        ];

        $clientId = config('keycloak.client_id');
        $secret = config('keycloak.secret');

        $response = Http::withBasicAuth($clientId, $secret)
            ->asForm()
            ->post($uri, $payload);

        if ($response->ok()) {
            return ($response['active'] ?? 'false') == "true";
        }

        $logMessage = "Error while trying to validate the access token: {$accessToken}";
        Log::alert($logMessage);

        return false;
    }

    private static function createUri(string $endpoint): string {
        $keycloakHost = config('keycloak.host');
        return $keycloakHost . $endpoint;
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
