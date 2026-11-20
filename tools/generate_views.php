<?php

$base = dirname(__DIR__).'/resources/views/tenant';

function w(string $path, string $content): void
{
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($path, $content);
    echo "Wrote $path\n";
}

w("$base/companies/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Companies')
@section('content')
<div class="d-flex justify-content-between mb-4"><h1 class="page-title mb-0">Companies</h1><a href="{{ route('tenant.companies.create') }}" class="btn btn-sehat-primary">Add Company</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search"></div><div class="col-md-2"><button class="btn btn-sehat-teal">Search</button></div></form>
<table class="table sehat-table"><thead><tr><th>Name</th><th>Contractors</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($companies as $company)
<tr>
<td>{{ $company->name }}</td>
<td>@if($company->contractors_count>0)<a href="{{ route('tenant.contractors.index',['company_id'=>$company->id]) }}">{{ $company->contractors_count }}</a>@else 0 @endif</td>
<td><span class="badge-sehat badge-{{ $company->status }}">{{ $company->status }}</span></td>
<td class="text-end">
<a href="{{ route('tenant.companies.edit',$company) }}" class="btn btn-sm btn-outline-primary">Edit</a>
@if($company->contractors_count>0)
<button class="btn btn-sm btn-outline-danger" disabled title="Cannot delete: contractors are associated">Delete</button>
@else
<form action="{{ route('tenant.companies.destroy',$company) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete company?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
@endif
</td></tr>
@empty<tr><td colspan="4" class="text-center text-muted">No companies</td></tr>@endforelse
</tbody></table>{{ $companies->links() }}</div>
@endsection
BLADE);

w("$base/companies/create.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Add Company')
@section('content')
<h1 class="page-title mb-4">Add Company</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.companies.store') }}" enctype="multipart/form-data">@csrf
@include('tenant.companies._form')
<button class="btn btn-sehat-primary mt-3">Save</button></form></div>
@endsection
BLADE);

w("$base/companies/edit.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Edit Company')
@section('content')
<h1 class="page-title mb-4">Edit Company</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.companies.update',$company) }}" enctype="multipart/form-data">@csrf @method('PUT')
@include('tenant.companies._form')
<button class="btn btn-sehat-primary mt-3">Update</button></form></div>
@endsection
BLADE);

w("$base/companies/_form.blade.php", <<<'BLADE'
@php($company=$company??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Company name</label><input name="name" class="form-control" value="{{ old('name',$company?->name) }}" required></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$company?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$company?->status)==='inactive')>Inactive</option></select></div>
<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$company?->address) }}</textarea></div>
<div class="col-md-6"><label class="form-label">Logo</label><input type="file" name="logo" class="form-control" accept="image/*"></div>
<div class="col-md-6"><label class="form-label">Remark</label><input name="remark" class="form-control" value="{{ old('remark',$company?->remark) }}"></div>
</div>
BLADE);

w("$base/contractors/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Contractors')
@section('content')
<div class="d-flex justify-content-between mb-4"><h1 class="page-title mb-0">Contractors</h1><a href="{{ route('tenant.contractors.create') }}" class="btn btn-sehat-primary">Add Contractor</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET">
<div class="col-md-3"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search"></div>
<div class="col-md-3"><select name="company_id" class="form-select"><option value="">All companies</option>@foreach($companies as $c)<option value="{{ $c->id }}" @selected(request('company_id')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
<div class="col-md-2"><button class="btn btn-sehat-teal">Filter</button></div>
</form>
<table class="table sehat-table"><thead><tr><th>Code</th><th>Name</th><th>Mobile</th><th>Companies</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($contractors as $contractor)
<tr>
<td><code>{{ $contractor->contractor_code }}</code></td>
<td>{{ $contractor->name }}</td>
<td>{{ $contractor->mobile }}</td>
<td>{{ $contractor->companies->pluck('name')->join(', ') }}</td>
<td><span class="badge-sehat badge-{{ $contractor->status }}">{{ $contractor->status }}</span></td>
<td class="text-end"><a href="{{ route('tenant.contractors.edit',$contractor) }}" class="btn btn-sm btn-outline-primary">Edit</a>
<form action="{{ route('tenant.contractors.destroy',$contractor) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted">No contractors</td></tr>@endforelse
</tbody></table>{{ $contractors->links() }}</div>
@endsection
BLADE);

w("$base/contractors/create.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Add Contractor')
@section('content')
<h1 class="page-title mb-4">Add Contractor</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.contractors.store') }}">@csrf @include('tenant.contractors._form')
<button class="btn btn-sehat-primary mt-3">Save</button></form></div>
@endsection
BLADE);

w("$base/contractors/edit.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Edit Contractor')
@section('content')
<h1 class="page-title mb-4">Edit Contractor</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.contractors.update',$contractor) }}">@csrf @method('PUT') @include('tenant.contractors._form')
<button class="btn btn-sehat-primary mt-3">Update</button></form></div>
@endsection
BLADE);

w("$base/contractors/_form.blade.php", <<<'BLADE'
@php($contractor=$contractor??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name',$contractor?->name) }}" required></div>
<div class="col-md-3"><label class="form-label">Mobile</label><input name="mobile" class="form-control" value="{{ old('mobile',$contractor?->mobile) }}" required></div>
<div class="col-md-3"><label class="form-label">Alternate mobile</label><input name="alternate_mobile" class="form-control" value="{{ old('alternate_mobile',$contractor?->alternate_mobile) }}"></div>
<div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$contractor?->email) }}"></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$contractor?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$contractor?->status)==='inactive')>Inactive</option></select></div>
<div class="col-12"><label class="form-label">Associated companies</label>
<select name="companies[]" class="form-select" multiple required size="5">
@foreach($companies as $company)
<option value="{{ $company->id }}" @selected(collect(old('companies',$contractor?->companies?->pluck('id')->all()??[]))->contains($company->id))>{{ $company->name }}</option>
@endforeach
</select></div>
<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$contractor?->address) }}</textarea></div>
<div class="col-12"><label class="form-label">Remarks</label><textarea name="remarks" class="form-control" rows="2">{{ old('remarks',$contractor?->remarks) }}</textarea></div>
</div>
BLADE);

w("$base/patients/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Patients')
@section('content')
<div class="d-flex justify-content-between mb-4"><h1 class="page-title mb-0">Patients</h1><a href="{{ route('tenant.patients.create') }}" class="btn btn-sehat-primary">Add Patient</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name/code/mobile"></div><div class="col-md-2"><button class="btn btn-sehat-teal">Search</button></div></form>
<table class="table sehat-table"><thead><tr><th>Code</th><th>Name</th><th>Contractor</th><th>Charges</th><th>Mobile</th><th></th></tr></thead><tbody>
@forelse($patients as $patient)
<tr>
<td><code>{{ $patient->patient_code }}</code></td>
<td>{{ $patient->name }}</td>
<td>{{ $patient->contractor?->name ?? '—' }}</td>
<td>₹{{ number_format($patient->charges,2) }}</td>
<td>{{ $patient->mobile }}</td>
<td class="text-end"><a href="{{ route('tenant.patients.show',$patient) }}" class="btn btn-sm btn-outline-secondary">View</a>
<a href="{{ route('tenant.patients.edit',$patient) }}" class="btn btn-sm btn-outline-primary">Edit</a></td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted">No patients</td></tr>@endforelse
</tbody></table>{{ $patients->links() }}</div>
@endsection
BLADE);

w("$base/patients/create.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Add Patient')
@section('content')
<h1 class="page-title mb-4">Add Patient @if($parent)<span class="fs-6 text-muted">Family of {{ $parent->name }}</span>@endif</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.patients.store') }}">@csrf
@if($parent)<input type="hidden" name="parent_patient_id" value="{{ $parent->id }}"><input type="hidden" name="family_relation" value="child">@endif
@include('tenant.patients._form')
<button class="btn btn-sehat-primary mt-3">Save Patient</button></form></div>
@endsection
BLADE);

w("$base/patients/edit.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Edit Patient')
@section('content')
<h1 class="page-title mb-4">Edit Patient</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.patients.update',$patient) }}">@csrf @method('PUT')
@include('tenant.patients._form')
<button class="btn btn-sehat-primary mt-3">Update</button></form></div>
@endsection
BLADE);

w("$base/patients/_form.blade.php", <<<'BLADE'
@php($patient=$patient??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Patient name</label><input name="name" class="form-control" value="{{ old('name',$patient?->name) }}" required></div>
<div class="col-md-3"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">—</option>@foreach(['male','female','other'] as $g)<option value="{{ $g }}" @selected(old('gender',$patient?->gender)===$g)>{{ ucfirst($g) }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">Age</label><input type="number" name="age" class="form-control" value="{{ old('age',$patient?->age) }}"></div>
<div class="col-md-4"><label class="form-label">Mobile</label><input name="mobile" class="form-control" value="{{ old('mobile',$patient?->mobile) }}"></div>
<div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$patient?->email) }}"></div>
<div class="col-md-4"><label class="form-label">Blood group</label><input name="blood_group" class="form-control" value="{{ old('blood_group',$patient?->blood_group) }}"></div>
<div class="col-md-6"><label class="form-label">Contractor</label>
<select name="contractor_id" class="form-select"><option value="">—</option>
@foreach($contractors as $contractor)
<option value="{{ $contractor->id }}" @selected(old('contractor_id',$patient?->contractor_id)==$contractor->id)>{{ $contractor->name }} ({{ $contractor->companies->pluck('name')->join(', ') }})</option>
@endforeach
</select></div>
<div class="col-md-3"><label class="form-label">Charges</label><input type="number" step="0.01" name="charges" class="form-control" value="{{ old('charges',$patient?->charges ?? 0) }}"></div>
<div class="col-md-3"><label class="form-label">Date</label><input type="date" name="registration_date" class="form-control" value="{{ old('registration_date', optional($patient?->registration_date)->format('Y-m-d') ?? date('Y-m-d')) }}"></div>
<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$patient?->address) }}</textarea></div>
<div class="col-12"><label class="form-label">Allergies</label><textarea name="allergies" class="form-control" rows="2">{{ old('allergies',$patient?->allergies) }}</textarea></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$patient?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$patient?->status)==='inactive')>Inactive</option></select></div>
<div class="col-md-6"><label class="form-label">Notes</label><input name="notes" class="form-control" value="{{ old('notes',$patient?->notes) }}"></div>
</div>
BLADE);

w("$base/patients/show.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title',$patient->name)
@section('content')
<div class="d-flex justify-content-between mb-4 flex-wrap gap-2">
<div><h1 class="page-title mb-1">{{ $patient->name }}</h1><p class="text-muted mb-0"><code>{{ $patient->patient_code }}</code></p></div>
<div class="d-flex gap-2">
<a href="{{ route('tenant.patients.create',['parent_id'=>$patient->id]) }}" class="btn btn-sehat-teal">Add Family Member</a>
<a href="{{ route('tenant.patients.edit',$patient) }}" class="btn btn-outline-primary">Edit</a>
</div></div>
<div class="row g-3">
<div class="col-lg-8">
<div class="sehat-card mb-3"><h5>Profile</h5>
<div class="row g-2"><div class="col-md-4"><div class="text-muted small">Mobile</div>{{ $patient->mobile?:'—' }}</div>
<div class="col-md-4"><div class="text-muted small">Contractor</div>{{ $patient->contractor?->name?:'—' }}</div>
<div class="col-md-4"><div class="text-muted small">Charges</div>₹{{ number_format($patient->charges,2) }}</div>
<div class="col-12"><div class="text-muted small">Allergies</div>{{ $patient->allergies?:'—' }}</div></div></div>
<div class="sehat-card mb-3"><h5>Family members</h5>
@forelse($patient->familyMembers as $member)
<div class="d-flex justify-content-between border-bottom py-2"><div>{{ $member->name }} <span class="text-muted">({{ $member->family_relation }})</span></div><a href="{{ route('tenant.patients.show',$member) }}">View</a></div>
@empty<p class="text-muted mb-0">No family members.</p>@endforelse
</div>
<div class="sehat-card"><h5>Medical history</h5>
@forelse($patient->medicalHistories as $h)
<div class="border-bottom py-2"><strong>{{ $h->visit_date->format('d M Y') }}</strong> — {{ $h->diagnosis }}</div>
@empty<p class="text-muted mb-0">No history yet.</p>@endforelse
</div>
</div>
<div class="col-lg-4">
<div class="sehat-card mb-3"><h5>Appointments</h5>@forelse($patient->appointments->take(5) as $a)<div class="small border-bottom py-1">{{ $a->appointment_date->format('d M Y') }} {{ $a->appointment_time }}</div>@empty<p class="text-muted mb-0">None</p>@endforelse</div>
<div class="sehat-card"><h5>Prescriptions</h5>@forelse($patient->prescriptions->take(5) as $p)<div class="small border-bottom py-1"><a href="{{ route('tenant.prescriptions.show',$p) }}">{{ $p->prescription_no }}</a></div>@empty<p class="text-muted mb-0">None</p>@endforelse</div>
</div></div>
@endsection
BLADE);

echo "Core tenant views generated\n";
