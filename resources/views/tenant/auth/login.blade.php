@extends('layouts.auth')

@section('title', 'Clinic Login')

@section('auth_brand')
<div class="sehat-auth-brand">
    <div class="brand-icon"><i class="bi bi-hospital"></i></div>
    <div>
        <h1>Clinic Portal</h1>
        <p class="text-muted small mb-0">Sign in with your clinic credentials</p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('tenant.login') }}" method="POST" data-sehat-loading>
    @csrf
    <div class="mb-3">
        <label for="tenant_code" class="sehat-form-label">Clinic / Tenant Code</label>
        <div class="input-group">
            <span class="input-group-text sehat-input-group-text"><i class="bi bi-upc-scan"></i></span>
            <input type="text" name="tenant_code" id="tenant_code" class="form-control sehat-form-control text-uppercase" value="{{ old('tenant_code') }}" required autofocus placeholder="e.g. CLNC01">
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="sehat-form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control sehat-form-control" value="{{ old('email') }}" required autocomplete="username">
    </div>
    <div class="mb-3">
        <label for="password" class="sehat-form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control sehat-form-control" required autocomplete="current-password">
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="form-check">
            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label small" for="remember">Remember me</label>
        </span>
        <a href="{{ route('tenant.password.request') }}" class="small text-sehat-orange text-decoration-none">Forgot password?</a>
    </div>
    <button type="submit" class="btn btn-sehat-primary w-100 py-2" data-loading-text="Signing in…">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
    </button>
</form>
@endsection

