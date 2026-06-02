<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Role::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        $roles = $query->orderBy('name')->paginate(15)->appends($request->query());
        return view('roles.index', compact('roles', 'search'));
    }

    /** Show the form for creating a new role. */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('roles.create', compact('permissions'));
    }

    /** Store a newly created role. */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create(['name' => $request->input('name')]);
        if ($request->filled('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /** Display the specified role. */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('roles.show', compact('role'));
    }

    /** Show the form for editing the specified role. */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('name')->get();
        return view('roles.edit', compact('role', 'permissions'));
    }

    /** Update the specified role. */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permissions', []));
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /** Remove the specified role. */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
?>
