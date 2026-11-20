@extends('layouts.tenant')
@section('title','Prescriptions')
@section('page_title', 'Prescriptions')
@section('content')
<div class="sehat-page-header"><div><h1 class="page-title">Prescriptions</h1></div><a href="{{ route('tenant.prescriptions.create') }}" class="btn btn-sehat-primary">New Prescription</a></div>
<div class="sehat-card">
<table class="table sehat-table"><thead><tr><th>No</th><th>Patient</th><th>Doctor</th><th>Date</th><th></th></tr></thead><tbody>
@forelse($prescriptions as $prescription)
<tr><td><code>{{ $prescription->prescription_no }}</code></td><td>{{ $prescription->patient?->name }}</td><td>{{ $prescription->doctor?->name }}</td><td>{{ $prescription->prescribed_date->format('d M Y') }}</td>
<td class="text-end"><a href="{{ route('tenant.prescriptions.show',$prescription) }}" class="btn btn-sm btn-outline-secondary">View</a>
<a href="{{ route('tenant.prescriptions.pdf',$prescription) }}" class="btn btn-sm btn-outline-primary">PDF</a></td></tr>
@empty<tr><td colspan="5" class="text-center text-muted">No prescriptions</td></tr>@endforelse
</tbody></table>{{ $prescriptions->links() }}</div>
@endsection