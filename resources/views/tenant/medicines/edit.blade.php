@extends('layouts.tenant')
@section('title','Edit Medicine')
@section('page_title', 'Edit Medicine')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Edit Medicine</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.medicines.update',$medicine) }}">@csrf @method('PUT') @include('tenant.medicines._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Update</button></div></form></div>
@endsection