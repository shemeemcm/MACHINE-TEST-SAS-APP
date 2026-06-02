@extends('layouts.app')

@section('title', 'Organizations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-building me-2"></i>Organizations</h1>
    <a href="{{ route('organizations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> New Organization
    </a>
</div>

{{-- Search & Filter Bar --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('organizations.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" class="form-control"
                       placeholder="Search by name or domain..."
                       value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="sort_by" class="form-label">Sort By</label>
                <select name="sort_by" id="sort_by" class="form-select">
                    <option value="id" @selected($sortBy === 'id')>ID</option>
                    <option value="name" @selected($sortBy === 'name')>Name</option>
                    <option value="domain" @selected($sortBy === 'domain')>Domain</option>
                    <option value="created_at" @selected($sortBy === 'created_at')>Created At</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort_dir" class="form-label">Direction</label>
                <select name="sort_dir" id="sort_dir" class="form-select">
                    <option value="asc" @selected($sortDir === 'asc')>Ascending</option>
                    <option value="desc" @selected($sortDir === 'desc')>Descending</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="trashed" class="form-label">Status</label>
                <select name="trashed" id="trashed" class="form-select">
                    <option value="">Active Only</option>
                    <option value="with" @selected(($filters['trashed'] ?? '') === 'with')>With Trashed</option>
                    <option value="only" @selected(($filters['trashed'] ?? '') === 'only')>Only Trashed</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Apply
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Domain</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizations as $organization)
                    <tr @if($organization->trashed()) class="table-warning" @endif>
                        <td>{{ $organization->id }}</td>
                        <td class="fw-semibold">{{ $organization->name }}</td>
                        <td>
                            @if($organization->domain)
                                <span class="badge bg-info text-dark">{{ $organization->domain }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $organization->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($organization->trashed())
                                <span class="badge bg-danger">Trashed</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($organization->trashed())
                                <form action="{{ route('organizations.restore', $organization->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                <form action="{{ route('organizations.force-delete', $organization->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Permanently delete this organization? This cannot be undone!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Force Delete">
                                        <i class="bi bi-x-octagon"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('organizations.show', $organization->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('organizations.edit', $organization->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('organizations.destroy', $organization->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this organization?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            No organizations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($organizations->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Showing {{ $organizations->firstItem() }}–{{ $organizations->lastItem() }} of {{ $organizations->total() }}
            </small>
            {{ $organizations->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
