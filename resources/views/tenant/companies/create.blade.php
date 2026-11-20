@extends('layouts.tenant')
@section('title','Add Company')
@section('page_title', 'Add Company')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Add Company</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.companies.store') }}" enctype="multipart/form-data">@csrf
@include('tenant.companies._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Save</button></div></form></div>
@endsection
