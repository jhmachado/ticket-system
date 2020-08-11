<?php

namespace Modules\Ticket\Listener;

use Modules\Ticket\Event\TicketCreatedEvent;
use Modules\Ticket\Job\SendTicketCreatedEmailJob;

final class TicketCreatedListener
{
    public function handle(TicketCreatedEvent $event): void
    {
        SendTicketCreatedEmailJob::dispatch($event->getTicket())->onQueue('default');
    }
}
