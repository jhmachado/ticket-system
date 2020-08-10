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
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ticket');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'ticket');
    }
}
