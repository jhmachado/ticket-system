<?php

namespace Modules\Auth\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use Modules\Auth\Facades\AuthService;
use Modules\Auth\Services\KeyCloakAuthService;

final class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AuthRouteServiceProvider::registerRoutes();

        $this->loadFactoriesFrom(__DIR__ . '/../Database/factories');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

        $this->app->singleton(AuthService::class, function () {
            return new KeyCloakAuthService();
        });
    }
}
