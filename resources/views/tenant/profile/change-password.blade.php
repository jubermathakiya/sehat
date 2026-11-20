@extends('layouts.tenant')
@section('title','Change Password')
@section('page_title', 'Change Password')
@section('content')
<h1 class="page-title mb-4">Change Password</h1>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.password.change.update') }}">@csrf @method('PUT')
<div class="mb-3"><label class="form-label">Current password</label><input type="password" name="current_password" class="form-control" required></div>
<div class="mb-3"><label class="form-label">New password</label><input type="password" name="password" class="form-control" required><div class="form-text">Min 8 chars with upper, lower, number, symbol</div></div>
<div class="mb-3"><label class="form-label">Confirm password</label><input type="password" name="password_confirmation" class="form-control" required></div>
<button class="btn btn-sehat-primary">Update Password</button>
</form>
</div>
@endsection