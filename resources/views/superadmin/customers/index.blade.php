@extends('layouts.superadmin')
@section('title', 'Customers')
@section('page_title', 'Customers')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Customers</h1>
        <p class="sehat-page-subtitle mb-0">Manage clinics and tenant databases</p>
    </div>
    <a href="{{ route('superadmin.customers.create') }}" class="btn btn-sehat-primary">
        <i class="bi bi-plus-lg"></i> Add Customer
    </a>
</div>

<div class="sehat-card">
    <form class="row g-2 mb-3" method="GET">
        <div class="col-md-4">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search clinic, doctor, email, code">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach(['active','inactive','suspended','expired','failed','pending'] as $st)
                    <option value="{{ $st }}" @selected(request('status')===$st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-sehat-primary w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table sehat-table align-middle">
            <thead>
                <tr>
                    <th>Tenant</th>
                    <th>Clinic</th>
                    <th>Doctor</th>
                    <th>Plan</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td><code>{{ $customer->tenant_code }}</code></td>
                    <td>{{ $customer->clinic_name }}</td>
                    <td>{{ $customer->doctor_name }}</td>
                    <td>{{ $customer->plan?->name ?? '—' }}</td>
                    <td>{{ optional($customer->subscription_expiry_date)->format('d M Y') }}</td>
                    <td><span class="badge-sehat badge-{{ $customer->account_status }}">{{ $customer->account_status }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('superadmin.customers.show',$customer) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="{{ route('superadmin.customers.edit',$customer) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No customers yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $customers->links() }}
</div>
@endsection
