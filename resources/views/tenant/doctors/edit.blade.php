@extends('layouts.tenant')
@section('title','Edit Doctor')
@section('page_title', 'Edit Doctor')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Edit Doctor</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.doctors.update',$doctor) }}">@csrf @method('PUT') @include('tenant.doctors._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Update</button></div></form></div>
@endsection