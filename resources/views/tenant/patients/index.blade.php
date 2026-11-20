@extends('layouts.tenant')
@section('title','Patients')
@section('page_title', 'Patients')
@section('content')
<div class="sehat-page-header"><div><h1 class="page-title">Patients</h1></div><a href="{{ route('tenant.patients.create') }}" class="btn btn-sehat-primary">Add Patient</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name/code/mobile"></div><div class="col-md-2"><button class="btn btn-sehat-primary">Search</button></div></form>
<table class="table sehat-table"><thead><tr><th>Code</th><th>Name</th><th>Contractor</th><th>Charges</th><th>Mobile</th><th></th></tr></thead><tbody>
@forelse($patients as $patient)
<tr>
<td><code>{{ $patient->patient_code }}</code></td>
<td>{{ $patient->name }}</td>
<td>{{ $patient->contractor?->name ?? '—' }}</td>
<td>â‚¹{{ number_format($patient->charges,2) }}</td>
<td>{{ $patient->mobile }}</td>
<td class="text-end"><a href="{{ route('tenant.patients.show',$patient) }}" class="btn btn-sm btn-outline-secondary">View</a>
<a href="{{ route('tenant.patients.edit',$patient) }}" class="btn btn-sm btn-outline-primary">Edit</a></td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted">No patients</td></tr>@endforelse
</tbody></table>{{ $patients->links() }}</div>
@endsection
