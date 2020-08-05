<?php

namespace Modules\Auth\Facades;

interface AuthService
{
    public function login(string $username, string $password);
}
