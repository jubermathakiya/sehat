@extends('layouts.tenant')
@section('title','Edit Company')
@section('page_title', 'Edit Company')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Edit Company</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.companies.update',$company) }}" enctype="multipart/form-data">@csrf @method('PUT')
@include('tenant.companies._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Update</button></div></form></div>
@endsection
