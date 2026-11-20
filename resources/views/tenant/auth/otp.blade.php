@extends('layouts.auth')

@section('title', 'Verify OTP')

@section('auth_brand')
<div class="sehat-auth-brand">
    <div class="brand-icon"><i class="bi bi-shield-check"></i></div>
    <div>
        <h1>Enter OTP</h1>
        <p class="text-muted small mb-0">6-digit code sent to your email</p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('tenant.password.otp.verify') }}" method="POST" data-sehat-loading>
    @csrf
    @if(isset($tenant_code))
        <input type="hidden" name="tenant_code" value="{{ $tenant_code }}">
    @endif
    @if(isset($email))
        <input type="hidden" name="email" value="{{ $email }}">
    @endif
    <input type="hidden" name="otp" id="otp" value="">

    <div class="sehat-otp-group mb-3" data-sehat-otp data-sehat-otp-target="#otp">
        @for($i = 0; $i < 6; $i++)
            <input type="text" class="sehat-otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" aria-label="Digit {{ $i + 1 }}">
        @endfor
    </div>

    <p class="text-center small text-muted mb-3" data-sehat-otp-countdown data-seconds="{{ $otpExpiresIn ?? 120 }}">Resend available soon…</p>

    <button type="submit" class="btn btn-sehat-primary w-100 py-2 mb-3" data-loading-text="Verifying…">
        Verify & Continue
    </button>
</form>
<div class="text-center">
    <form action="{{ route('tenant.password.otp.resend') }}" method="POST">
        @csrf
        @if(isset($tenant_code))<input type="hidden" name="tenant_code" value="{{ $tenant_code }}">@endif
        @if(isset($email))<input type="hidden" name="email" value="{{ $email }}">@endif
        <button type="submit" class="btn btn-link text-sehat-teal text-decoration-none disabled" data-sehat-otp-resend>
            Resend OTP
        </button>
    </form>
</div>
@endsection

