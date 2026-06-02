@extends('layouts.guest')

@section('title', 'Forgot Password')
@section('subtitle', 'Reset your password')

@section('content')
<p class="text-muted mb-4" style="font-size:.85rem;">
    Enter your email address and we'll send you a link to reset your password.
</p>

<form method="POST" action="{{ route('password.email') }}">
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

    <!-- Email -->
    <div class="mb-4">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-icon-wrapper">
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@example.com" required autofocus>
        </div>
    </div>

    <!-- Submit -->
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-paper-plane me-2"></i> Send Reset Link
    </button>
</form>
@endsection

@section('footer')
<div class="auth-footer">
    Remember your password? <a href="{{ route('login') }}">Sign in</a>
</div>
@endsection
