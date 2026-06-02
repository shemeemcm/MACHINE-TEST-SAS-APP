@extends('layouts.app')

@section('title', 'Tickets')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tickets</li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Ticket Management</h1>
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search title or description...">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="priority" class="form-select">
                <option value="">All Priorities</option>
                @foreach($priorities as $p)
                    <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="assignee" class="form-select">
                <option value="">All Assignees</option>
                @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ request('assignee') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
        </div>
        <div class="col-md-1 text-end">
            <a href="{{ route('admin.tickets.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> New</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Assignee</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ ucfirst($ticket->status) }}</td>
                        <td>{{ ucfirst($ticket->priority) }}</td>
                        <td>{{ $ticket->assignee ? $ticket->assignee->name : '-' }}</td>
                        <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                            @if($ticket->status !== 'closed')
                                <form method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Close"><i class="fas fa-check"></i></button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" class="d-inline" onsubmit="return confirm('Delete this ticket?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">No tickets found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $tickets->appends(request()->query())->links() }}
</div>
@endsection
