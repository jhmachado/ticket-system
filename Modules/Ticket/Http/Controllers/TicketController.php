<?php

namespace Modules\Ticket\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Modules\Ticket\Http\Requests\CreateTicketRequest;
use Modules\Ticket\Http\Requests\UpdateTicketRequest;
use Modules\Ticket\Model\Ticket;
use Modules\Ticket\Repository\TicketRepository;
use Illuminate\Routing\Controller as BaseController;

final class TicketController extends BaseController
{
    public function queryTickets(TicketRepository $repository): JsonResponse
    {
        $ticketCollection = $repository->queryTickets($page = 0);
        return Response::json($ticketCollection, 200);
    }

    public function createTicket(CreateTicketRequest $request, TicketRepository $repository): JsonResponse
    {
        $newTicket = new Ticket();
        $newTicket->fill($request->input());
        $repository->save($newTicket);

        return Response::json($newTicket, 201);
    }

    public function updateTicket(UpdateTicketRequest $request, TicketRepository $repository, string $ticketId): JsonResponse
    {
        $ticket = $repository->retrieveTicketByIdOrCry($ticketId);
        $ticket->fill($request->input());
        $repository->save($ticket);

        return Response::json($ticket);
    }

    public function closeTicket()
    {

    }
}
