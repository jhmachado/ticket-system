<?php

namespace Ticket\Service;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class RouteServiceProvider extends ServiceProvider
{   
    public static function registerRoutes(): void
    {
        Route::namespace('\Ticket\Http\Controllers')
            ->prefix('ticket')    
            ->group(function () {
                Route::get('/', 'TicketController@queryTickets');
                Route::post('/', 'TicketController@createTicket');
                Route::put('/{ticket}', 'TicketController@updateTicket');
                Route::put('/{ticket}/close', 'TicketController@closeTicket');
            });
    }
}