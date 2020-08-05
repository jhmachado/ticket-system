<?php

namespace Modules\Ticket\ServiceProvider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class RouteServiceProvider extends ServiceProvider
{
    public static function registerRoutes(): void
    {
        Route::namespace('\Modules\Ticket\Http\Controllers')
            ->prefix('ticket')
            ->group(function () {
                Route::get('/', 'TicketController@queryTickets');
                Route::post('/', 'TicketController@createTicket');
                Route::put('/{ticketId}', 'TicketController@updateTicket');
                Route::put('/{ticketId}/close', 'TicketController@closeTicket');
            });
    }
}
