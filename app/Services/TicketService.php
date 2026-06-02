<?php

namespace App\Services;

use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Events\TicketCreated;

class TicketService
{
    protected $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function getAllTickets(int $perPage = 15)
    {
        // Return paginated tickets to avoid memory exhaustion
        return $this->ticketRepository->getPaginated([], $perPage);
    }

    public function createTicket(array $data)
    {
        try {
            $ticket = $this->ticketRepository->create($data);
            
            // Dispatch event after creation
            event(new TicketCreated($ticket));

            return $ticket;
        } catch (\Exception $e) {
            Log::error('Failed to create ticket: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function updateTicket(int $id, array $data)
    {
        // Business logic (e.g. checking status transition validity)
        return $this->ticketRepository->update($id, $data);
    }
}
