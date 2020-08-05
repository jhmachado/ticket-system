<?php

namespace Modules\Auth\ServiceProvider;

use Illuminate\Support\ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AuthRouteServiceProvider::registerRoutes();

        $this->loadFactoriesFrom(__DIR__ . '/../Database/factories');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}
