<?php

namespace App\Repositories;

use App\Interfaces\AuditLogRepositoryInterface;
use App\Models\AuditLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    /**
     * Get paginated audit logs with optional filters.
     *
     * Supported filters:
     * - search: string (search in user name, event, auditable_type)
     * - user_id: int
     * - action: string (event)
     * - module: string (auditable_type)
     * - date_from, date_to: dates
     */
    public function getPaginated(array $filters = [], int $perPage = 15, string $sortBy = 'created_at', string $sortDir = 'desc'): LengthAwarePaginator
    {
        $query = AuditLog::with(['user', 'auditable']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                  ->orWhere('auditable_type', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        if (!empty($filters['action'])) {
            $query->where('event', $filters['action']);
        }
        if (!empty($filters['module'])) {
            $query->where('auditable_type', $filters['module']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function getFilterOptions(): array
    {
        return [
            'users' => \App\Models\User::orderBy('name')->pluck('name', 'id')->toArray(),
            'actions' => AuditLog::select('event')->distinct()->pluck('event')->toArray(),
            'modules' => AuditLog::select('auditable_type')->distinct()->pluck('auditable_type')->toArray(),
        ];
    }

    public function find(int $id): ?AuditLog
    {
        return AuditLog::with(['user', 'auditable'])->find($id);
    }
}
?>
