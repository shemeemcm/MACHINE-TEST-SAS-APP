<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\TicketAttachment;

interface TicketAttachmentRepositoryInterface
{
    public function getAllByTicket(int $ticketId, int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?TicketAttachment;
    public function create(array $data, $file): TicketAttachment;
    public function delete(int $id): bool;
    public function getFilePath(int $id): string;
}
?>
