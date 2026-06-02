@extends('layouts.app')

@section('title', 'Ticket Comments')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.show', $ticket) }}">Ticket #{{ $ticket->id }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Comments</li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Comments for Ticket #{{ $ticket->id }}</h1>
    <a href="{{ route('admin.tickets.comments.create', $ticket) }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Add Comment</a>
    @forelse($comments as $comment)
        <div class="card mb-3">
            <div class="card-body">
                <p class="card-text">{{ $comment->comment }}</p>
                <p class="card-text"><small class="text-muted">By {{ $comment->user->name ?? 'Unknown' }} on {{ $comment->created_at->format('Y-m-d H:i') }}</small></p>
                <a href="{{ route('admin.tickets.comments.edit', [$ticket, $comment]) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i> Edit</a>
                <form method="POST" action="{{ route('admin.tickets.comments.destroy', [$ticket, $comment]) }}" class="d-inline" onsubmit="return confirm('Delete this comment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    @empty
        <p>No comments yet.</p>
    @endforelse
    {{ $comments->appends(request()->query())->links() }}
</div>
@endsection
