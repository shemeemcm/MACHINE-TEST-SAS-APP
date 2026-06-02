@extends('layouts.app')

@section('title', 'Ticket Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
    <li class="breadcrumb-item active" aria-current="page">#{{ $ticket->id }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Ticket #{{ $ticket->id }}</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $ticket->title }}</h5>
            <p class="card-text"><strong>Description:</strong><br>{{ nl2br(e($ticket->description)) }}</p>
            <p class="card-text"><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
            <p class="card-text"><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
            <p class="card-text"><strong>Creator:</strong> {{ $ticket->creator->name ?? 'N/A' }}</p>
            <p class="card-text"><strong>Assignee:</strong> {{ $ticket->assignee ? $ticket->assignee->name : 'Unassigned' }}</p>
            <p class="card-text"><strong>Created At:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
            <p class="card-text"><strong>Updated At:</strong> {{ $ticket->updated_at->format('Y-m-d H:i') }}</p>
            <div class="mt-3">
                <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                @if($ticket->status !== 'closed')
                    <form method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="d-inline ms-2" onsubmit="return confirm('Close this ticket?');">
                        @csrf
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Close</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" class="d-inline ms-2" onsubmit="return confirm('Delete this ticket?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary ms-2"><i class="fas fa-arrow-left"></i> Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
