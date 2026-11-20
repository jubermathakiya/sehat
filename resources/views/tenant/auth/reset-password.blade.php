@extends('layouts.auth')

@section('title', 'New Password')

@section('auth_brand')
<div class="sehat-auth-brand">
    <div class="brand-icon"><i class="bi bi-lock"></i></div>
    <div>
        <h1>Set New Password</h1>
        <p class="text-muted small mb-0">Choose a strong password</p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('tenant.password.update') }}" method="POST" data-sehat-loading>
    @csrf
    @if(isset($token))
        <input type="hidden" name="token" value="{{ $token }}">
    @endif
    @if(isset($tenant_code))
        <input type="hidden" name="tenant_code" value="{{ $tenant_code }}">
    @endif
    <div class="mb-3">
        <label for="email" class="sehat-form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control sehat-form-control" value="{{ old('email', $email ?? '') }}" required readonly>
    </div>
    <div class="mb-3">
        <label for="password" class="sehat-form-label">New Password</label>
        <input type="password" name="password" id="password" class="form-control sehat-form-control" required autocomplete="new-password">
    </div>
    <div class="mb-4">
        <label for="password_confirmation" class="sehat-form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control sehat-form-control" required autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn-sehat-primary w-100 py-2" data-loading-text="Updating…">
        Update Password
    </button>
</form>
@endsection

