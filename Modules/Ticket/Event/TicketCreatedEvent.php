<?php

namespace Modules\Ticket\Event;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Ticket\Model\Ticket;

final class TicketCreatedEvent
{
    use Dispatchable;

    private Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function getTicket(): Ticket {
        return $this->ticket;
    }
}
