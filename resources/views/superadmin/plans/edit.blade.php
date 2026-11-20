@extends('layouts.superadmin')
@section('title', 'Edit Plan')
@section('page_title', 'Edit Plan')
@section('content')
<div class="sehat-page-header">
    <h2>Edit {{ $plan->name }}</h2>
    <a href="{{ route('superadmin.plans.index') }}" class="btn btn-sehat-ghost">Back</a>
</div>
<div class="sehat-card">
    <div class="sehat-card-body">
        <form action="{{ route('superadmin.plans.update', $plan) }}" method="POST" data-sehat-loading>
            @csrf @method('PUT')
            @include('superadmin.plans._form', ['plan' => $plan])
            <div class="mt-4">
                <button type="submit" class="btn btn-sehat-primary" data-loading-text="Updating…">Update Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection
