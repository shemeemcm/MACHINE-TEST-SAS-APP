@extends('layouts.guest')

@section('title', 'Register')
@section('subtitle', 'Create your account')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <div class="input-icon-wrapper">
            <i class="fas fa-user"></i>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="John Doe" required autofocus autocomplete="name">
        </div>
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-icon-wrapper">
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@example.com" required autocomplete="username">
        </div>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
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
        <i class="fas fa-user-plus me-2"></i> Create Account
    </button>
</form>
@endsection

@section('footer')
<div class="auth-footer">
    Already have an account? <a href="{{ route('login') }}">Sign in</a>
</div>
@endsection
