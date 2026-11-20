@extends('layouts.superadmin')
@section('title', 'Add Customer')
@section('page_title', 'Add Customer')
@section('content')
<div class="sehat-page-header">
    <p class="sehat-page-subtitle mb-0">Provision a new clinic with its own isolated database</p>
    <a href="{{ route('superadmin.customers.index') }}" class="btn btn-sehat-ghost">Back to list</a>
</div>

<div class="sehat-card">
    <form method="POST" action="{{ route('superadmin.customers.store') }}" data-sehat-loading>
        @csrf
        @include('superadmin.customers._form')
        <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-sehat-primary" data-loading-text="Provisioning…">
                <i class="bi bi-database-add"></i> Create & Provision Database
            </button>
            <a href="{{ route('superadmin.customers.index') }}" class="btn btn-sehat-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
