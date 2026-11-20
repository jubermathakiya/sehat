@extends('layouts.superadmin')
@section('title', 'Add Plan')
@section('page_title', 'Add Plan')
@section('content')
<div class="sehat-page-header">
    <h2>New Plan</h2>
    <a href="{{ route('superadmin.plans.index') }}" class="btn btn-sehat-ghost">Back</a>
</div>
<div class="sehat-card">
    <div class="sehat-card-body">
        <form action="{{ route('superadmin.plans.store') }}" method="POST" data-sehat-loading>
            @csrf
            @include('superadmin.plans._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-sehat-primary" data-loading-text="Saving…">Create Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection
