@extends('layouts.superadmin')
@section('title', 'Edit Customer')
@section('page_title', 'Edit Customer')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Edit Customer</h1>
        <p class="sehat-page-subtitle mb-0">{{ $customer->clinic_name }} · <code>{{ $customer->tenant_code }}</code></p>
    </div>
    <a href="{{ route('superadmin.customers.show', $customer) }}" class="btn btn-sehat-ghost">Back</a>
</div>

<div class="sehat-card">
    <form method="POST" action="{{ route('superadmin.customers.update', $customer) }}" data-sehat-loading>
        @csrf
        @method('PUT')
        @include('superadmin.customers._form', ['editing' => true])
        <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-sehat-primary" data-loading-text="Saving…">Update</button>
            <a href="{{ route('superadmin.customers.show', $customer) }}" class="btn btn-sehat-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
