<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * List users with pagination, search and role filter.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $role   = $request->query('role');
        $sortBy = $request->query('sort_by', 'id');
        $sortDir = $request->query('sort_dir', 'desc');
        $perPage = $request->query('per_page', 15);

        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        if ($role) {
            $filters['role'] = $role;
        }

        $users = $this->userRepo->getAll($filters, $perPage, $sortBy, $sortDir);
        $roles = Role::orderBy('name')->pluck('name', 'id');

        return view('users.index', compact('users', 'search', 'sortBy', 'sortDir', 'role', 'roles'));
    }

    /** Show form to create a new user */
    public function create()
    {
        $roles = Role::orderBy('name')->pluck('name', 'id');
        return view('users.create', compact('roles'));
    }

    /** Store new user */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // Hash password
        $validated['password'] = bcrypt($validated['password']);

        $user = $this->userRepo->create($validated);

        // Assign roles if any
        if ($request->filled('roles')) {
            $this->userRepo->assignRoles($user->id, $request->input('roles'));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /** Display a single user */
    public function show($id)
    {
        $user = $this->userRepo->findById($id);
        return view('users.show', compact('user'));
    }

    /** Show edit form */
    public function edit($id)
    {
        $user = $this->userRepo->findById($id);
        $roles = Role::orderBy('name')->pluck('name', 'id');
        $assignedRoles = $user->roles->pluck('id')->toArray();
        return view('users.edit', compact('user', 'roles', 'assignedRoles'));
    }

    /** Update user */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$id",
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $this->userRepo->update($id, $validated);

        // Sync roles if provided
        if ($request->filled('roles')) {
            $this->userRepo->syncRoles($id, $request->input('roles'));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /** Delete user */
    public function destroy($id)
    {
        $this->userRepo->delete($id);
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
?>
