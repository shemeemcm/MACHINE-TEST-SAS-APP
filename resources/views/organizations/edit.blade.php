@extends('layouts.app')

@section('title', 'Edit Organization')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Organization</h1>
            <a href="{{ route('organizations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('organizations.update', $organization->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $organization->name) }}" placeholder="Enter organization name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="domain" class="form-label">Domain</label>
                        <input type="text" name="domain" id="domain"
                               class="form-control @error('domain') is-invalid @enderror"
                               value="{{ old('domain', $organization->domain) }}" placeholder="e.g. acme.helpdesk.com">
                        @error('domain')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional. Unique subdomain for this organization.</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('organizations.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Organization
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
