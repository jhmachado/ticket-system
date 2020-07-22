<?php

namespace Ticket\Repository;

use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Ticket\Model\Ticket;

final class TicketRepository
{
    private const ITENS_PER_PAGE = 15;

    private const COLUMNS_TO_DISPLAY = ['*'];

    private const PAGE_NAME = 'tickets';

    public function createTicket(string $title, string $description): Ticket
    {
        $ticket = new Ticket();

        $ticket->id = Str::uuid();
        $ticket->title = $title;
        $ticket->description = $description;

        $ticket->save();

        return $ticket;
    }

    public function queryTickets(string $page): Paginator
    {
        return Ticket::simplePaginate(
            self::ITENS_PER_PAGE,
            self::COLUMNS_TO_DISPLAY,
            self::PAGE_NAME,
            $page
        );
    }
}