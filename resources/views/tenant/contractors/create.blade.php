@extends('layouts.tenant')
@section('title','Add Contractor')
@section('page_title', 'Add Contractor')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Add Contractor</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.contractors.store') }}">@csrf @include('tenant.contractors._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Save</button></div></form></div>
@endsection
