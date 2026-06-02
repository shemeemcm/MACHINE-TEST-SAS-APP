<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Services\OrganizationService;
use Illuminate\Http\Request;
use Exception;

class OrganizationController extends Controller
{
    protected $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Display a listing of organizations.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'trashed']);
        $perPage = (int) $request->get('per_page', 15);
        $sortBy  = $request->get('sort_by', 'id');
        $sortDir = $request->get('sort_dir', 'desc');

        $organizations = $this->organizationService->getOrganizations($filters, $perPage, $sortBy, $sortDir);

        return view('organizations.index', compact('organizations', 'filters', 'sortBy', 'sortDir'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created organization.
     */
    public function store(StoreOrganizationRequest $request)
    {
        try {
            $this->organizationService->createOrganization($request->validated());
            return redirect()->route('organizations.index')
                ->with('success', 'Organization created successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified organization.
     */
    public function show(int $id)
    {
        $organization = $this->organizationService->getOrganizationById($id);
        return view('organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit(int $id)
    {
        $organization = $this->organizationService->getOrganizationById($id);
        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization.
     */
    public function update(UpdateOrganizationRequest $request, int $id)
    {
        try {
            $this->organizationService->updateOrganization($id, $request->validated());
            return redirect()->route('organizations.index')
                ->with('success', 'Organization updated successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Soft delete the specified organization.
     */
    public function destroy(int $id)
    {
        try {
            $this->organizationService->deleteOrganization($id);
            return redirect()->route('organizations.index')
                ->with('success', 'Organization deleted successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Restore a soft-deleted organization.
     */
    public function restore(int $id)
    {
        try {
            $this->organizationService->restoreOrganization($id);
            return redirect()->route('organizations.index')
                ->with('success', 'Organization restored successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Permanently delete an organization.
     */
    public function forceDelete(int $id)
    {
        try {
            $this->organizationService->forceDeleteOrganization($id);
            return redirect()->route('organizations.index')
                ->with('success', 'Organization permanently deleted.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
