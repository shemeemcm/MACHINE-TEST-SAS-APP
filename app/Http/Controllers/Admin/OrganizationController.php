<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    protected $orgRepo;

    public function __construct(OrganizationRepository $orgRepo)
    {
        $this->orgRepo = $orgRepo;
    }

    /**
     * Display a listing of the organizations.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'id');
        $sortDir = $request->query('sort_dir', 'desc');
        $perPage = $request->query('per_page', 15);

        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        if ($request->query('trashed')) {
            $filters['trashed'] = $request->query('trashed');
        }

        $organizations = $this->orgRepo->getPaginated($filters, $perPage, $sortBy, $sortDir);

        return view('organizations.index', compact('organizations', 'search', 'sortBy', 'sortDir'));
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:organizations,domain',
        ]);

        $this->orgRepo->create($validated);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    /**
     * Display the specified organization.
     */
    public function show($id)
    {
        $organization = $this->orgRepo->getById($id);
        return view('organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit($id)
    {
        $organization = $this->orgRepo->getById($id);
        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:organizations,domain,' . $id,
        ]);

        $this->orgRepo->update($id, $validated);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified organization.
     */
    public function destroy($id)
    {
        $this->orgRepo->delete($id);
        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }
}
?>
