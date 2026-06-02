<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\AuditLog;

interface AuditLogRepositoryInterface
{
    public function getPaginated(array $filters = [], int $perPage = 15, string $sortBy = 'created_at', string $sortDir = 'desc'): LengthAwarePaginator;
    public function getFilterOptions(): array;
    public function find(int $id): ?AuditLog;
}
?>
