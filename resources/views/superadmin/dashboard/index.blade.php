@extends('layouts.superadmin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="sehat-stat-grid mb-4">
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon teal"><i class="bi bi-building"></i></div>
        <div class="sehat-stat-label">Total Customers</div>
        <div class="sehat-stat-value">{{ $stats['total_customers'] ?? 0 }}</div>
    </div>
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon teal"><i class="bi bi-check-circle"></i></div>
        <div class="sehat-stat-label">Active</div>
        <div class="sehat-stat-value">{{ $stats['active_customers'] ?? 0 }}</div>
    </div>
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon orange"><i class="bi bi-clock-history"></i></div>
        <div class="sehat-stat-label">Trial</div>
        <div class="sehat-stat-value">{{ $stats['trial_customers'] ?? 0 }}</div>
    </div>
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon orange"><i class="bi bi-x-circle"></i></div>
        <div class="sehat-stat-label">Expired</div>
        <div class="sehat-stat-value">{{ $stats['expired_customers'] ?? 0 }}</div>
    </div>
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon navy"><i class="bi bi-currency-rupee"></i></div>
        <div class="sehat-stat-label">Revenue</div>
        <div class="sehat-stat-value">₹{{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="sehat-card">
            <div class="sehat-card-header">
                <span>Recent Customers</span>
                <a href="{{ route('superadmin.customers.index') }}" class="btn btn-sm btn-sehat-outline">View all</a>
            </div>
            <div class="sehat-card-body p-0">
                <div class="sehat-table-wrap">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Clinic</th>
                                <th>Code</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCustomers ?? [] as $customer)
                                <tr>
                                    <td>
                                        <a href="{{ route('superadmin.customers.show', $customer) }}" class="text-decoration-none fw-semibold text-sehat-navy">
                                            {{ $customer->clinic_name }}
                                        </a>
                                        <div class="small text-muted">{{ $customer->email }}</div>
                                    </td>
                                    <td><code>{{ $customer->tenant_code }}</code></td>
                                    <td>{{ $customer->plan->name ?? '—' }}</td>
                                    <td>
                                        @include('partials.sehat-status-badge', ['status' => $customer->account_status])
                                    </td>
                                    <td class="text-muted small">{{ $customer->created_at?->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No customers yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="sehat-card h-100">
            <div class="sehat-card-header">Upcoming Expiries</div>
            <div class="sehat-card-body">
                <ul class="list-unstyled mb-0">
                    @forelse($upcomingExpiries ?? [] as $customer)
                        <li class="d-flex justify-content-between align-items-start py-2 border-bottom">
                            <div>
                                <strong class="d-block small">{{ $customer->clinic_name }}</strong>
                                <span class="text-muted small">{{ $customer->tenant_code }}</span>
                            </div>
                            <span class="sehat-badge sehat-badge-pending">
                                {{ $customer->subscription_expiry_date?->format('M d') }}
                            </span>
                        </li>
                    @empty
                        <li class="text-muted small">No expiries in the next period.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

