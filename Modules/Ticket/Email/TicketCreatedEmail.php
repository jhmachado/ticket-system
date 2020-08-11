<?php

namespace Modules\Ticket\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Ticket\Model\Ticket;

final class TicketCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build(): self
    {
        return $this->markdown('ticket::email.ticket-created');
    }
}
