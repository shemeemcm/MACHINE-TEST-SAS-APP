<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Services\OrganizationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;

class OrganizationController extends Controller
{
    use ApiResponse;

    protected $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Display a listing of organizations with pagination, search, sort, and filter.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['search', 'trashed']);
            $perPage = (int) $request->get('per_page', 15);
            $sortBy  = $request->get('sort_by', 'id');
            $sortDir = $request->get('sort_dir', 'desc');

            $organizations = $this->organizationService->getOrganizations($filters, $perPage, $sortBy, $sortDir);

            return $this->successResponse(
                OrganizationResource::collection($organizations)->response()->getData(true),
                'Organizations retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve organizations', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created organization.
     */
    public function store(StoreOrganizationRequest $request)
    {
        try {
            $organization = $this->organizationService->createOrganization($request->validated());

            return $this->successResponse(
                new OrganizationResource($organization),
                'Organization created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create organization', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified organization.
     */
    public function show(int $id)
    {
        try {
            $organization = $this->organizationService->getOrganizationById($id);

            return $this->successResponse(
                new OrganizationResource($organization),
                'Organization retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Organization not found', $e->getMessage(), 404);
        }
    }

    /**
     * Update the specified organization.
     */
    public function update(UpdateOrganizationRequest $request, int $id)
    {
        try {
            $organization = $this->organizationService->updateOrganization($id, $request->validated());

            return $this->successResponse(
                new OrganizationResource($organization),
                'Organization updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update organization', $e->getMessage(), 500);
        }
    }

    /**
     * Soft delete the specified organization.
     */
    public function destroy(int $id)
    {
        try {
            $this->organizationService->deleteOrganization($id);

            return $this->successResponse(null, 'Organization deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete organization', $e->getMessage(), 500);
        }
    }

    /**
     * Restore a soft-deleted organization.
     */
    public function restore(int $id)
    {
        try {
            $this->organizationService->restoreOrganization($id);

            return $this->successResponse(null, 'Organization restored successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to restore organization', $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete an organization.
     */
    public function forceDelete(int $id)
    {
        try {
            $this->organizationService->forceDeleteOrganization($id);

            return $this->successResponse(null, 'Organization permanently deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to permanently delete organization', $e->getMessage(), 500);
        }
    }
}
