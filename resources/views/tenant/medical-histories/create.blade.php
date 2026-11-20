@extends('layouts.tenant')
@section('title','Add Medical History')
@section('page_title', 'Add Medical History')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Add Medical History</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.medical-histories.store') }}" enctype="multipart/form-data">@csrf @include('tenant.medical-histories._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Save</button></div></form></div>
@endsection