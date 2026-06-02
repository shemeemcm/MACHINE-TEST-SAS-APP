<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Build base query with optional filters.
     */
    protected function buildQuery(array $filters = []): Builder
    {
        $query = User::query();

        // Search by name or email
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if (!empty($filters['role'])) {
            $role = $filters['role'];
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        return $query;
    }

    public function getAll(array $filters = [], int $perPage = 15, string $sortBy = 'id', string $sortDir = 'desc')
    {
        return $this->buildQuery($filters)
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }

    public function getAllRaw()
    {
        return User::all();
    }

    public function findById(int $id): ?User
    {
        return User::with(['roles', 'permissions'])->find($id);
    }

    public function create(array $data): User
    {
        // Assume password is already hashed if provided
        return User::create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function assignRoles(int $userId, array $roleIds): User
    {
        $user = User::findOrFail($userId);
        $roles = Role::whereIn('id', $roleIds)->get();
        $user->assignRole($roles);
        return $user;
    }

    public function syncRoles(int $userId, array $roleIds): User
    {
        $user = User::findOrFail($userId);
        $roles = Role::whereIn('id', $roleIds)->get();
        $user->syncRoles($roles);
        return $user;
    }

    public function assignPermissions(int $userId, array $permissionIds): User
    {
        $user = User::findOrFail($userId);
        $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $permissionIds)->get();
        $user->givePermissionTo($permissions);
        return $user;
    }

    public function syncPermissions(int $userId, array $permissionIds): User
    {
        $user = User::findOrFail($userId);
        $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $permissionIds)->get();
        $user->syncPermissions($permissions);
        return $user;
    }

    public function countAll(): int
    {
        return User::count();
    }
}
?>
