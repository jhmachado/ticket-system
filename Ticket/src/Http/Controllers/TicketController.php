<?php

namespace Ticket\Http\Controllers;

use Ticket\Http\Requests\CreateTicketRequest;
use Ticket\Model\Ticket;

final class TicketController
{
    public function queryTickets()
    {
        return response()->json(["test" => "test"]);
    }

    public function create(CreateTicketRequest $request)
    {

    }

    public function updateTicket()
    {
        
    }

    public function closeTicket()
    {
        
    }
}