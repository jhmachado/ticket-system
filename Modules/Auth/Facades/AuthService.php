<?php

namespace Modules\Auth\Facades;

use Modules\Auth\Models\AccessToken;

interface AuthService
{
    public function login(string $username, string $password): ?AccessToken;

    public function validateAccessToken(string $accessToken): bool;
}
