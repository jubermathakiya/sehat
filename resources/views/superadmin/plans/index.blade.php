@extends('layouts.superadmin')
@section('title', 'Plans')
@section('page_title', 'Subscription Plans')
@section('content')
<div class="sehat-page-header">
    <div>
        <h2>Plans</h2>
        <p class="sehat-breadcrumb">Pricing and limits</p>
    </div>
    <a href="{{ route('superadmin.plans.create') }}" class="btn btn-sehat-primary"><i class="bi bi-plus-lg me-1"></i> Add Plan</a>
</div>
<div class="sehat-card">
    <div class="sehat-card-body p-0">
        <div class="sehat-table-wrap">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Monthly</th>
                        <th>Yearly</th>
                        <th>Trial</th>
                        <th>Limits</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans ?? [] as $plan)
                        <tr>
                            <td class="fw-semibold">{{ $plan->name }}</td>
                            <td>₹{{ number_format($plan->monthly_price, 2) }}</td>
                            <td>₹{{ number_format($plan->yearly_price, 2) }}</td>
                            <td>{{ $plan->trial_days }}d</td>
                            <td class="small text-muted">Dr {{ $plan->max_doctors }} · Staff {{ $plan->max_staff }}</td>
                            <td>@include('partials.sehat-status-badge', ['status' => $plan->status])</td>
                            <td class="text-end">
                                <a href="{{ route('superadmin.plans.edit', $plan) }}" class="btn btn-sm btn-sehat-outline"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('superadmin.plans.destroy', $plan) }}" method="POST" class="d-inline" data-sehat-confirm-delete>
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">No plans yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

