<?php

namespace App\Interfaces;

interface TicketRepositoryInterface
{
    public function getAll();
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function countAll(): int;
    public function countByStatus(string $status): int;
    public function countByStatuses(array $statuses): int;
    public function buildQuery(array $filters = []); // returns Builder
    public function getPaginated(array $filters = [], int $perPage = 15, string $sortBy = 'id', string $sortDir = 'desc');
    public function assign(int $ticketId, int $userId);
    public function reassign(int $ticketId, int $userId);
    public function close(int $ticketId);

    public function getStatusCounts(): array;
}
