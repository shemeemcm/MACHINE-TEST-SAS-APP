<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * List permissions.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Permission::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        $permissions = $query->orderBy('name')->paginate(15)->appends($request->query());
        return view('permissions.index', compact('permissions', 'search'));
    }

    /** Show form to create permission. */
    public function create()
    {
        return view('permissions.create');
    }

    /** Store new permission. */
    public function store(StorePermissionRequest $request)
    {
        Permission::create(['name' => $request->input('name'), 'guard_name' => 'web']);
        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /** Show a permission. */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.show', compact('permission'));
    }

    /** Show edit form. */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    /** Update permission. */
    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->name = $request->input('name');
        $permission->save();
        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /** Delete permission. */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
?>
