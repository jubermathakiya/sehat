@extends('layouts.tenant')
@section('title','Profile')
@section('page_title', 'Profile')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">My Profile</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.profile.update') }}">@csrf @method('PUT')
<div class="row g-3">
<div class="col-md-6"><label class="form-label">First name</label><input name="first_name" class="form-control" value="{{ old('first_name',$user->first_name) }}" required></div>
<div class="col-md-6"><label class="form-label">Last name</label><input name="last_name" class="form-control" value="{{ old('last_name',$user->last_name) }}" required></div>
<div class="col-md-6"><label class="form-label">Email</label><input class="form-control" value="{{ $user->email }}" disabled></div>
<div class="col-md-6"><label class="form-label">Mobile</label><input name="mobile" class="form-control" value="{{ old('mobile',$user->mobile) }}"></div>
<div class="col-md-6"><label class="form-label">Subscription ID</label><input class="form-control" value="{{ $user->subscription_id }}" disabled></div>
</div>
<button class="btn btn-sehat-primary mt-3">Update Profile</button>
<a href="{{ route('tenant.password.change') }}" class="btn btn-outline-secondary mt-3">Change Password</a>
</form>
</div>
@endsection