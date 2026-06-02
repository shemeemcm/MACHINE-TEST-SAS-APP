<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 15, string $sortBy = 'id', string $sortDir = 'desc'): LengthAwarePaginator;

    public function findById(int $id): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): User;

    public function delete(int $id): bool;

    public function assignRoles(int $userId, array $roleIds): User;

    public function syncRoles(int $userId, array $roleIds): User;

    public function assignPermissions(int $userId, array $permissionIds): User;

    public function syncPermissions(int $userId, array $permissionIds): User;
    public function countAll(): int;
}

?>
