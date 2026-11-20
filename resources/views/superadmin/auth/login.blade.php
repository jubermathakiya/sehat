@extends('layouts.auth')

@section('title', 'Super Admin Login')

@section('auth_brand')
<div class="sehat-auth-brand">
    <div class="brand-icon"><i class="bi bi-shield-lock"></i></div>
    <div>
        <h1>Super Admin</h1>
        <p class="text-muted small mb-0">Sign in to manage clinics & plans</p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('superadmin.login') }}" method="POST" data-sehat-loading>
    @csrf
    <div class="mb-3">
        <label for="email" class="sehat-form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control sehat-form-control" value="{{ old('email') }}" required autofocus autocomplete="username">
    </div>
    <div class="mb-3">
        <label for="password" class="sehat-form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control sehat-form-control" required autocomplete="current-password">
    </div>
    <div class="mb-4 form-check">
        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label small" for="remember">Remember me</label>
    </div>
    <button type="submit" class="btn btn-sehat-primary w-100 py-2" data-loading-text="Signing in…">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
    </button>
</form>
@endsection

