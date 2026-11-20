@extends('layouts.superadmin')
@section('title',$customer->clinic_name)
@section('page_title', $customer->clinic_name)
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">{{ $customer->clinic_name }}</h1>
        <p class="sehat-page-subtitle mb-0">Tenant <code>{{ $customer->tenant_code }}</code> · {{ $customer->doctor_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('superadmin.customers.edit',$customer) }}" class="btn btn-sehat-outline">Edit</a>
        <a href="{{ route('superadmin.customers.index') }}" class="btn btn-sehat-ghost">Back</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="sehat-card mb-3">
            <h5 class="mb-3">Account details</h5>
            <div class="row g-3">
                <div class="col-md-6"><div class="text-muted small">Email</div><div>{{ $customer->email }}</div></div>
                <div class="col-md-6"><div class="text-muted small">Mobile</div><div>{{ $customer->mobile ?: '—' }}</div></div>
                <div class="col-md-6"><div class="text-muted small">Username</div><div>{{ $customer->username }}</div></div>
                <div class="col-md-6"><div class="text-muted small">Status</div><div><span class="badge-sehat badge-{{ $customer->account_status }}">{{ $customer->account_status }}</span></div></div>
                <div class="col-md-6"><div class="text-muted small">Plan</div><div>{{ $customer->plan?->name }} ({{ $customer->billing_cycle }})</div></div>
                <div class="col-md-6"><div class="text-muted small">Expiry</div><div>{{ optional($customer->subscription_expiry_date)->format('d M Y') }} · Grace {{ $customer->grace_period_days }}d</div></div>
                <div class="col-12"><div class="text-muted small">Address</div><div>{{ $customer->address }} {{ $customer->city }} {{ $customer->state }} {{ $customer->country }}</div></div>
            </div>
        </div>
        <div class="sehat-card mb-3">
            <h5 class="mb-3">Subscription history</h5>
            <div class="table-responsive">
                <table class="table sehat-table">
                    <thead><tr><th>Plan</th><th>Cycle</th><th>Start</th><th>Expiry</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($customer->subscriptions as $sub)
                        <tr>
                            <td>{{ $sub->plan?->name }}</td>
                            <td>{{ $sub->billing_cycle }}</td>
                            <td>{{ $sub->start_date->format('d M Y') }}</td>
                            <td>{{ $sub->expiry_date->format('d M Y') }}</td>
                            <td>{{ $sub->status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="sehat-card">
            <h5 class="mb-3">Account logs</h5>
            <ul class="list-unstyled mb-0">
                @forelse($customer->logs->take(20) as $log)
                    <li class="border-bottom py-2"><strong>{{ $log->action }}</strong> — {{ $log->description }} <span class="text-muted small">{{ $log->created_at }}</span></li>
                @empty
                    <li class="text-muted">No logs yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="sehat-card mb-3">
            <h5 class="mb-3">Database</h5>
            @if($customer->database)
                <div class="mb-2"><span class="text-muted small">Name</span><div><code>{{ $customer->database->db_name }}</code></div></div>
                <div class="mb-2"><span class="text-muted small">Host</span><div>{{ $customer->database->db_host }}:{{ $customer->database->db_port }}</div></div>
                <div><span class="text-muted small">Status</span><div><span class="badge-sehat badge-{{ $customer->database->status==='ready'?'active':'expired' }}">{{ $customer->database->status }}</span></div></div>
            @else
                <p class="text-muted mb-0">No database record.</p>
            @endif
            @if($customer->provisioning_error)
                <div class="alert alert-danger mt-3 mb-0 small">{{ $customer->provisioning_error }}</div>
            @endif
        </div>
        <div class="sehat-card mb-3">
            <h5 class="mb-3">Renew subscription</h5>
            <form method="POST" action="{{ route('superadmin.customers.renew',$customer) }}">
                @csrf
                <div class="mb-2">
                    <select name="billing_cycle" class="form-select" required>
                        @foreach(['monthly','quarterly','half_yearly','yearly'] as $c)
                            <option value="{{ $c }}">{{ ucfirst(str_replace('_',' ',$c)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2"><input type="number" step="0.01" name="discount" class="form-control" placeholder="Discount amount"></div>
                <div class="mb-3"><textarea name="notes" class="form-control" rows="2" placeholder="Notes"></textarea></div>
                <button class="btn btn-sehat-accent w-100">Renew & Restore Access</button>
            </form>
        </div>
        <div class="sehat-card">
            <h5 class="mb-3">Reset password</h5>
            <form method="POST" action="{{ route('superadmin.customers.reset-password',$customer) }}">
                @csrf
                <div class="mb-2"><input type="password" name="password" class="form-control" placeholder="New password" required></div>
                <div class="mb-3"><input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required></div>
                <button class="btn btn-sehat-primary w-100">Reset Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

