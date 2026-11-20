@extends('layouts.tenant')
@section('title',$patient->name)
@section('page_title', $patient->name)
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
<div class="col-md-4"><div class="text-muted small">Charges</div>â‚¹{{ number_format($patient->charges,2) }}</div>
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
