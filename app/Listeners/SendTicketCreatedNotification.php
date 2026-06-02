<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Jobs\ProcessTicketNotification;

class SendTicketCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketCreated $event): void
    {
        // Dispatch job to queue to prevent blocking the HTTP request
        ProcessTicketNotification::dispatch($event->ticket);
    }
}
