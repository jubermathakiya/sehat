@extends('layouts.tenant')
@section('title','Edit Contractor')
@section('page_title', 'Edit Contractor')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Edit Contractor</h1>
    </div>
</div>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.contractors.update',$contractor) }}">@csrf @method('PUT') @include('tenant.contractors._form')
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">Update</button></div></form></div>
@endsection
