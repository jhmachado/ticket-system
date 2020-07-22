<?php

namespace Ticket\Service;

use Illuminate\Support\ServiceProvider;

final class ResourceServiceProvider extends ServiceProvider
{
    public function publishResourceFiles(): void
    {
        $this->publishes([
            __DIR__ . '/../../resources/lang/en/messages.php' => resource_path('/lang/vendor/ticket-module/en/messages.php'),
            __DIR__ . '/../../resources/lang/pt-br/messages.php' => resource_path('/lang/vendor/ticket-module/pt-br/messages.php'),
        ]);
    }
}
