<?php

namespace App\Repositories;

use App\Interfaces\OrganizationRepositoryInterface;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    protected function buildQuery(array $filters = []): Builder
    {
        $query = Organization::query();

        // Check if we need trashed records
        if (isset($filters['trashed'])) {
            if ($filters['trashed'] === 'with') {
                $query->withTrashed();
            } elseif ($filters['trashed'] === 'only') {
                $query->onlyTrashed();
            }
        }

        // Search logic
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('domain', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function getPaginated(array $filters = [], int $perPage = 15, string $sortBy = 'id', string $sortDir = 'desc')
    {
        return $this->buildQuery($filters)
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }

    public function getAll()
    {
        return Organization::paginate(15);
    }

    public function getAllRaw()
    {
        return Organization::all();
    }

    public function getById(int $id)
    {
        return Organization::withTrashed()->findOrFail($id);
    }

    public function create(array $data)
    {
        return Organization::create($data);
    }

    public function update(int $id, array $data)
    {
        $organization = Organization::withTrashed()->findOrFail($id);
        $organization->update($data);
        return $organization;
    }

    public function delete(int $id)
    {
        return Organization::findOrFail($id)->delete();
    }

    public function restore(int $id)
    {
        return Organization::onlyTrashed()->findOrFail($id)->restore();
    }

    public function forceDelete(int $id)
    {
        return Organization::withTrashed()->findOrFail($id)->forceDelete();
    }
    public function countAll(): int
    {
        return Organization::count();
    }
}

