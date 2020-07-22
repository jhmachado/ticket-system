<?php

namespace Ticket\Service;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        RouteServiceProvider::registerRoutes();

        $this->loadFactoriesFrom(__DIR__.'/../../database/factories');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
