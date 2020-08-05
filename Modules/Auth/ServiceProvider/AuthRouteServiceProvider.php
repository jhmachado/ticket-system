<?php

namespace Modules\Auth\ServiceProvider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class AuthRouteServiceProvider extends ServiceProvider
{
    public static function registerRoutes(): void
    {
        Route::namespace('\Modules\Auth\Http\Controllers')
            ->prefix('auth')
            ->group(function () {
                Route::post('/login', 'AuthController@logUserIn');
                Route::post('/logout', 'AuthController@logUserOut');
            });
    }
}
