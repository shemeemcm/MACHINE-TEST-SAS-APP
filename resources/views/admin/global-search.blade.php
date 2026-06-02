<?php
/**
 * resources/views/admin/global-search.blade.php
 */
?>
@extends('layouts.app')

@section('title', 'Global Search')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Search</li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Global Search</h1>
    <form method="GET" action="{{ route('admin.search') }}" class="row g-3 mb-4">
        <div class="col-md-10">
            <input type="text" name="q" class="form-control" placeholder="Search organizations, users, tickets..." value="{{ $search ?? '' }}" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Search</button>
        </div>
    </form>

    @if(isset($results) && $search)
        @foreach($results as $type => $collection)
            <h3 class="mt-5 text-capitalize">{{ $type }}</h3>
            @if($collection->isEmpty())
                <p class="text-muted">No {{ $type }} found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            @if($type === 'organizations')
                                <th>#</th>
                                <th>Name</th>
                                <th>Domain</th>
                            @elseif($type === 'users')
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                            @elseif($type === 'tickets')
                                <th>#</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Priority</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($collection as $item)
                            <tr>
                                @if($type === 'organizations')
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->domain }}</td>
                                @elseif($type === 'users')
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                @elseif($type === 'tickets')
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ ucfirst($item->status) }}</td>
                                    <td>{{ ucfirst($item->priority) }}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $collection->appends(request()->query())->links() }}
            @endif
        @endforeach
    @endif
</div>
@endsection
