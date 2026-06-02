<?php

namespace App\Services;

use App\Interfaces\OrganizationRepositoryInterface;
use Exception;

class OrganizationService
{
    protected $repository;

    public function __construct(OrganizationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getOrganizations(array $filters = [], int $perPage = 15, string $sortBy = 'id', string $sortDir = 'desc')
    {
        return $this->repository->getPaginated($filters, $perPage, $sortBy, $sortDir);
    }

    public function getOrganizationById(int $id)
    {
        return $this->repository->getById($id);
    }

    public function createOrganization(array $data)
    {
        // Add business logic (e.g., domain formatting/validation) if needed
        return $this->repository->create($data);
    }

    public function updateOrganization(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteOrganization(int $id)
    {
        return $this->repository->delete($id);
    }

    public function restoreOrganization(int $id)
    {
        return $this->repository->restore($id);
    }

    public function forceDeleteOrganization(int $id)
    {
        return $this->repository->forceDelete($id);
    }
}
