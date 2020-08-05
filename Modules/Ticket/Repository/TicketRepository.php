<?php

namespace Modules\Ticket\Repository;

use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Modules\Ticket\Model\Ticket;

final class TicketRepository
{
    private const ITEMS_PER_PAGE = 15;

    private const COLUMNS_TO_DISPLAY = ['*'];

    private const PAGE_NAME = 'page';

    public function save(Ticket $ticket): Ticket
    {
        if (empty($ticket->id)) {
            $ticket->id = Str::uuid();
        }

        $ticket->save();

        return $ticket;
    }

    public function queryTickets(string $page): Paginator
    {
            return Ticket::simplePaginate(
            self::ITEMS_PER_PAGE,
            self::COLUMNS_TO_DISPLAY,
            self::PAGE_NAME,
            $page
        );
    }
}
