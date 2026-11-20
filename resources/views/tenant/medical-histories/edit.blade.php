@extends('layouts.tenant')
@section('title','Edit Medical History')
@section('page_title', 'Edit Medical History')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Edit Medical History</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.medical-histories.update',$history) }}">@csrf @method('PUT') @include('tenant.medical-histories._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Update</button></div></form></div>
@endsection