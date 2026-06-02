<?php
/**
 * resources/views/roles/index.blade.php
 */
?>
@extends('layouts.app')

@section('title', 'Roles')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Roles</li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Roles</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <form method="GET" action="{{ route('admin.roles.index') }}" class="d-flex">
                <input type="text" name="search" value="{{ $search }}" class="form-control me-2" placeholder="Search roles...">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="col-md-8 text-end">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> New Role</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Permissions</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline" onsubmit="return confirm('Delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $roles->appends(request()->query())->links() }}
</div>
@endsection
