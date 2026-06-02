<?php

namespace App\Repositories;

use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;

class TicketRepository implements TicketRepositoryInterface
{
    public function getAll(int $perPage = 15)
    {
        // Return a paginated list to avoid loading all records into memory
        return Ticket::paginate($perPage);
    }

    public function getById(int $id)
    {
        return Ticket::findOrFail($id);
    }

    public function create(array $data)
    {
        return Ticket::create($data);
    }

    public function update(int $id, array $data)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);
        
        return $ticket;
    }

    public function delete(int $id)
    {
        return Ticket::destroy($id);
    }
    public function countAll(): int
    {
        return Ticket::count();
    }

    public function countByStatus(string $status): int
    {
        return Ticket::where('status', $status)->count();
    }

    public function countByStatuses(array $statuses): int
    {
        return Ticket::whereIn('status', $statuses)->count();
    }

    public function buildQuery(array $filters = [])
    {
        $query = Ticket::query();
        // Implement filtering logic here
        return $query;
    }

    public function getPaginated(array $filters = [], int $perPage = 15, string $sortBy = 'id', string $sortDir = 'desc')
    {
        return $this->buildQuery($filters)->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function assign(int $ticketId, int $userId)
    {
        return Ticket::where('id', $ticketId)->update(['assignee_id' => $userId]);
    }

    public function reassign(int $ticketId, int $userId)
    {
        return $this->assign($ticketId, $userId);
    }

    public function close(int $ticketId)
    {
        return Ticket::where('id', $ticketId)->update(['status' => 'closed']);
    }

    public function getStatusCounts(): array
    {
        return Ticket::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}

