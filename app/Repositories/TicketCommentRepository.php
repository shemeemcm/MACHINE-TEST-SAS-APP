<?php

namespace App\Repositories;

use App\Interfaces\TicketCommentRepositoryInterface;
use App\Models\TicketComment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TicketCommentRepository implements TicketCommentRepositoryInterface
{
    public function getAllByTicket(int $ticketId, array $filters = [], int $perPage = 15, string $sortBy = 'created_at', string $sortDir = 'desc'): LengthAwarePaginator
    {
        $query = TicketComment::where('ticket_id', $ticketId);
        // additional filters can be added here
        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function findById(int $id): ?TicketComment
    {
        return TicketComment::find($id);
    }

    public function create(array $data): TicketComment
    {
        return TicketComment::create($data);
    }

    public function update(int $id, array $data): TicketComment
    {
        $comment = TicketComment::findOrFail($id);
        $comment->update($data);
        return $comment;
    }

    public function delete(int $id): bool
    {
        return TicketComment::destroy($id) > 0;
    }
}
?>
