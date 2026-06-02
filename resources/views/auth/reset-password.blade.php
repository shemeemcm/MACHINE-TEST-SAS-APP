@extends('layouts.guest')

@section('title', 'Reset Password')
@section('subtitle', 'Set a new password')

@section('content')
<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-icon-wrapper">
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control" required autofocus autocomplete="username">
        </div>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <div class="input-icon-wrapper">
            <i class="fas fa-lock"></i>
            <input id="password" type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="new-password">
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <div class="input-icon-wrapper">
            <i class="fas fa-lock"></i>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required autocomplete="new-password">
        </div>
    </div>

    <!-- Submit -->
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-key me-2"></i> Reset Password
    </button>
</form>
@endsection
