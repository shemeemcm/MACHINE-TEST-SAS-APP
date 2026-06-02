@extends('layouts.app')

@section('title', 'Create Ticket')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Create New Ticket</h1>
    <form method="POST" action="{{ route('admin.tickets.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            @error('title')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
            @error('description')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3 row">
            <div class="col-md-4">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select" required>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ old('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="priority">Priority</label>
                <select name="priority" id="priority" class="form-select" required>
                    @foreach($priorities as $p)
                        <option value="{{ $p }}" {{ old('priority') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                @error('priority')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="assignee_id">Assignee (optional)</label>
                <select name="assignee_id" id="assignee_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" {{ old('assignee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('assignee_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Create Ticket</button>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
