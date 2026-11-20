@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('auth_brand')
<div class="sehat-auth-brand">
    <div class="brand-icon"><i class="bi bi-key"></i></div>
    <div>
        <h1>Reset Password</h1>
        <p class="text-muted small mb-0">We'll send an OTP to your email</p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('tenant.password.email') }}" method="POST" data-sehat-loading>
    @csrf
    <div class="mb-3">
        <label for="tenant_code" class="sehat-form-label">Clinic / Tenant Code</label>
        <input type="text" name="tenant_code" id="tenant_code" class="form-control sehat-form-control text-uppercase" value="{{ old('tenant_code') }}" required>
    </div>
    <div class="mb-4">
        <label for="email" class="sehat-form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control sehat-form-control" value="{{ old('email') }}" required>
    </div>
    <button type="submit" class="btn btn-sehat-accent w-100 py-2 mb-3" data-loading-text="Sending…">
        Send OTP
    </button>
    <a href="{{ route('tenant.login') }}" class="btn btn-sehat-ghost w-100">
        <i class="bi bi-arrow-left me-1"></i> Back to login
    </a>
</form>
@endsection

