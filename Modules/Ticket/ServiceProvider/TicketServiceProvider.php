<?php

namespace Modules\Ticket\ServiceProvider;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class TicketServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        RouteServiceProvider::registerRoutes();

        $this->loadFactoriesFrom(__DIR__ . '/../Database/factories');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}
