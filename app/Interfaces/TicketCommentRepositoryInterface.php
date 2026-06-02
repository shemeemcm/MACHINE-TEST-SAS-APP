<?php

namespace App\Interfaces;

use App\Models\TicketComment;
use Illuminate\Pagination\LengthAwarePaginator;

interface TicketCommentRepositoryInterface
{
    public function getAllByTicket(int $ticketId, array $filters = [], int $perPage = 15, string $sortBy = 'created_at', string $sortDir = 'desc'): LengthAwarePaginator;

    public function findById(int $id): ?TicketComment;

    public function create(array $data): TicketComment;

    public function update(int $id, array $data): TicketComment;

    public function delete(int $id): bool;
}
?>
