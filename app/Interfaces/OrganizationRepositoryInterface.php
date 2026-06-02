<?php

namespace App\Interfaces;

interface OrganizationRepositoryInterface
{
    public function getPaginated(array $filters = [], int $perPage = 15, string $sortBy = 'id', string $sortDir = 'desc');
    public function getAll();
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function restore(int $id);
    public function forceDelete(int $id);
    public function countAll(): int;
}

