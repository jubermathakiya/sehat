@extends('layouts.tenant')
@section('title','Add Patient')
@section('page_title', 'Add Patient')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Add Patient @if($parent)<span class="fs-6 text-muted">Family of {{ $parent->name }}</span>@endif</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.patients.store') }}">@csrf
@if($parent)<input type="hidden" name="parent_patient_id" value="{{ $parent->id }}"><input type="hidden" name="family_relation" value="child">@endif
@include('tenant.patients._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Save Patient</button></div></form></div>
@endsection
