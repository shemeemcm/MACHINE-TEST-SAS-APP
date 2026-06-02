@extends('layouts.app')

@section('title', 'Organization Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="bi bi-building me-2"></i>Organization Details</h1>
            <div>
                <a href="{{ route('organizations.edit', $organization->id) }}" class="btn btn-outline-warning">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('organizations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th class="text-muted" style="width: 200px;">ID</th>
                            <td>{{ $organization->id }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Name</th>
                            <td class="fw-semibold">{{ $organization->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Domain</th>
                            <td>
                                @if($organization->domain)
                                    <span class="badge bg-info text-dark">{{ $organization->domain }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status</th>
                            <td>
                                @if($organization->trashed())
                                    <span class="badge bg-danger">Trashed</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Created At</th>
                            <td>{{ $organization->created_at->format('F d, Y \a\t h:i A') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Updated At</th>
                            <td>{{ $organization->updated_at->format('F d, Y \a\t h:i A') }}</td>
                        </tr>
                        @if($organization->deleted_at)
                        <tr>
                            <th class="text-muted">Deleted At</th>
                            <td class="text-danger">{{ $organization->deleted_at->format('F d, Y \a\t h:i A') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card mt-4 border-danger">
            <div class="card-header bg-danger bg-opacity-10 text-danger fw-semibold">
                <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
            </div>
            <div class="card-body">
                <form action="{{ route('organizations.destroy', $organization->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this organization?')">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Delete this organization</strong>
                            <p class="text-muted mb-0 small">Once deleted, the organization will be soft-deleted and can be restored later.</p>
                        </div>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
