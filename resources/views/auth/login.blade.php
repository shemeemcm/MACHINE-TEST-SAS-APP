@extends('layouts.guest')

@section('title', 'Login')
@section('subtitle', 'Sign in to your account')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @error('email')
            <div class="alert alert-danger mb-3" role="alert">
                {{ $message }}
            </div>
        @enderror
        @error('password')
            <div class="alert alert-danger mb-3" role="alert">
                {{ $message }}
            </div>
        @enderror

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-icon-wrapper">
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@example.com" required autofocus autocomplete="username">
        </div>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-icon-wrapper">
            <i class="fas fa-lock"></i>
            <input id="password" type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
        </div>
    </div>

    <!-- Remember + Forgot -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
            <label class="form-check-label" for="remember_me" style="font-size:.85rem;">Remember me</label>
        </div>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" style="font-size:.85rem;color:#3b82f6;text-decoration:none;font-weight:500;">Forgot password?</a>
        @endif
    </div>

    <!-- Submit -->
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-sign-in-alt me-2"></i> Sign In
    </button>
</form>
@endsection

@section('footer')
<div class="auth-footer">
    Don't have an account? <a href="{{ route('register') }}">Create one</a>
</div>
@endsection
