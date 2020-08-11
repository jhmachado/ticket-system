@component('mail::message')

    A new ticket was created: "{{ $ticket->title }}".
    @component('mail::button', ['url' => url('/ticket/' . $ticket->id)])
        View ticket.
    @endcomponent
@endcomponent
