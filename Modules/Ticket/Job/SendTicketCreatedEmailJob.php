<?php

namespace Modules\Ticket\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Ticket\Email\TicketCreatedEmail;
use Modules\Ticket\Model\Ticket;

final class SendTicketCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(Ticket $ticket): void
    {
        $email = new TicketCreatedEmail($ticket);
        Mail::to('jhmachado12@gmail.com')->send($email);
    }
}
