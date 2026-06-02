@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit Ticket #{{ $ticket->id }}</h1>
    <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label" for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $ticket->title) }}" required>
            @error('title')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" rows="5" class="form-control" required>{{ old('description', $ticket->description) }}</textarea>
            @error('description')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3 row">
            <div class="col-md-4">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select" required>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ old('status', $ticket->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="priority">Priority</label>
                <select name="priority" id="priority" class="form-select" required>
                    @foreach($priorities as $p)
                        <option value="{{ $p }}" {{ old('priority', $ticket->priority) == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                @error('priority')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="assignee_id">Assignee (optional)</label>
                <select name="assignee_id" id="assignee_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" {{ old('assignee_id', $ticket->assignee_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('assignee_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Ticket</button>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Cancel</a>
        @if($ticket->status !== 'closed')
            <form method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="d-inline ms-2" onsubmit="return confirm('Close this ticket?');">
                @csrf
                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Close Ticket</button>
            </form>
        @endif
    </form>
</div>
@endsection
