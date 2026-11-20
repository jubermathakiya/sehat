<?php

$base = dirname(__DIR__).'/resources/views/tenant';
$emails = dirname(__DIR__).'/resources/views/emails';

function w(string $path, string $content): void
{
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    file_put_contents($path, $content);
    echo "Wrote $path\n";
}

w("$base/doctors/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Doctors')
@section('content')
<div class="d-flex justify-content-between mb-4"><h1 class="page-title mb-0">Doctors</h1><a href="{{ route('tenant.doctors.create') }}" class="btn btn-sehat-primary">Add Doctor</a></div>
<div class="sehat-card">
<table class="table sehat-table"><thead><tr><th>Name</th><th>Specialization</th><th>Mobile</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($doctors as $doctor)
<tr><td>{{ $doctor->name }}</td><td>{{ $doctor->specialization?->name ?: '—' }}</td><td>{{ $doctor->mobile }}</td>
<td><span class="badge-sehat badge-{{ $doctor->status }}">{{ $doctor->status }}</span></td>
<td class="text-end"><a href="{{ route('tenant.doctors.edit',$doctor) }}" class="btn btn-sm btn-outline-primary">Edit</a>
<form class="d-inline" method="POST" action="{{ route('tenant.doctors.destroy',$doctor) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
@empty<tr><td colspan="5" class="text-center text-muted">No doctors</td></tr>@endforelse
</tbody></table>{{ $doctors->links() }}</div>
@endsection
BLADE);

w("$base/doctors/create.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Add Doctor')
@section('content')
<h1 class="page-title mb-4">Add Doctor</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.doctors.store') }}">@csrf @include('tenant.doctors._form')
<button class="btn btn-sehat-primary mt-3">Save</button></form></div>
@endsection
BLADE);

w("$base/doctors/edit.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Edit Doctor')
@section('content')
<h1 class="page-title mb-4">Edit Doctor</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.doctors.update',$doctor) }}">@csrf @method('PUT') @include('tenant.doctors._form')
<button class="btn btn-sehat-primary mt-3">Update</button></form></div>
@endsection
BLADE);

w("$base/doctors/_form.blade.php", <<<'BLADE'
@php($doctor=$doctor??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name',$doctor?->name) }}" required></div>
<div class="col-md-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$doctor?->email) }}"></div>
<div class="col-md-3"><label class="form-label">Mobile</label><input name="mobile" class="form-control" value="{{ old('mobile',$doctor?->mobile) }}"></div>
<div class="col-md-6"><label class="form-label">Specialization</label>
<select name="specialization_id" class="form-select"><option value="">—</option>@foreach($specializations as $s)<option value="{{ $s->id }}" @selected(old('specialization_id',$doctor?->specialization_id)==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Or new specialization</label><input name="specialization_name" class="form-control" value="{{ old('specialization_name') }}"></div>
<div class="col-12"><label class="form-label">Qualification</label><input name="qualification" class="form-control" value="{{ old('qualification',$doctor?->qualification) }}"></div>
<div class="col-12"><label class="form-label">Bio</label><textarea name="bio" class="form-control" rows="2">{{ old('bio',$doctor?->bio) }}</textarea></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$doctor?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$doctor?->status)==='inactive')>Inactive</option></select></div>
</div>
BLADE);

w("$base/appointments/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Appointments')
@section('content')
<div class="d-flex justify-content-between mb-4"><h1 class="page-title mb-0">Appointments</h1><a href="{{ route('tenant.appointments.create') }}" class="btn btn-sehat-primary">Book Appointment</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET">
<div class="col-md-3"><input type="date" name="date" value="{{ request('date') }}" class="form-control"></div>
<div class="col-md-3"><select name="doctor_id" class="form-select"><option value="">All doctors</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(request('doctor_id')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-2"><button class="btn btn-sehat-teal">Filter</button></div>
</form>
<table class="table sehat-table"><thead><tr><th>Date</th><th>Time</th><th>Patient</th><th>Doctor</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($appointments as $appointment)
<tr>
<td>{{ $appointment->appointment_date->format('d M Y') }}</td>
<td>{{ substr($appointment->appointment_time,0,5) }}</td>
<td>{{ $appointment->patient?->name }}</td>
<td>{{ $appointment->doctor?->name }}</td>
<td><span class="badge-sehat badge-{{ $appointment->status==='cancelled'?'expired':($appointment->status==='completed'?'active':'trial') }}">{{ $appointment->status }}</span></td>
<td class="text-end"><a href="{{ route('tenant.appointments.edit',$appointment) }}" class="btn btn-sm btn-outline-primary">Edit</a>
<form class="d-inline" method="POST" action="{{ route('tenant.appointments.destroy',$appointment) }}" onsubmit="return confirm('Cancel appointment?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Cancel</button></form></td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted">No appointments</td></tr>@endforelse
</tbody></table>{{ $appointments->links() }}</div>
@endsection
BLADE);

w("$base/appointments/create.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Book Appointment')
@section('content')
<h1 class="page-title mb-4">Book Appointment</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.appointments.store') }}">@csrf @include('tenant.appointments._form')
<button class="btn btn-sehat-primary mt-3">Book</button></form></div>
@endsection
BLADE);

w("$base/appointments/edit.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Edit Appointment')
@section('content')
<h1 class="page-title mb-4">Edit Appointment</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.appointments.update',$appointment) }}">@csrf @method('PUT') @include('tenant.appointments._form')
<button class="btn btn-sehat-primary mt-3">Update</button></form></div>
@endsection
BLADE);

w("$base/appointments/_form.blade.php", <<<'BLADE'
@php($appointment=$appointment??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Patient</label><select name="patient_id" class="form-select" required><option value="">Select</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id',$appointment?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Doctor</label><select name="doctor_id" class="form-select" required><option value="">Select</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id',$appointment?->doctor_id)==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Date</label><input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date', optional($appointment?->appointment_date)->format('Y-m-d') ?? date('Y-m-d')) }}" required></div>
<div class="col-md-4"><label class="form-label">Time</label><input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time', $appointment?->appointment_time ? substr($appointment->appointment_time,0,5) : '10:00') }}" required></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['scheduled','confirmed','completed','cancelled','no_show'] as $s)<option value="{{ $s }}" @selected(old('status',$appointment?->status??'scheduled')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
<div class="col-12"><label class="form-label">Reason</label><input name="reason" class="form-control" value="{{ old('reason',$appointment?->reason) }}"></div>
<div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$appointment?->notes) }}</textarea></div>
</div>
BLADE);

w("$base/medicines/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Medicines')
@section('content')
<div class="d-flex justify-content-between mb-4"><h1 class="page-title mb-0">Medicines</h1><a href="{{ route('tenant.medicines.create') }}" class="btn btn-sehat-primary">Add Medicine</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search"></div><div class="col-md-2"><button class="btn btn-sehat-teal">Search</button></div></form>
<table class="table sehat-table"><thead><tr><th>Name</th><th>Category</th><th>Manufacturer</th><th>Stock</th><th>Price</th><th></th></tr></thead><tbody>
@forelse($medicines as $medicine)
<tr><td>{{ $medicine->name }}</td><td>{{ $medicine->category?->name?:'—' }}</td><td>{{ $medicine->manufacturer }}</td><td>{{ $medicine->stock }}</td><td>₹{{ number_format($medicine->price,2) }}</td>
<td class="text-end"><a href="{{ route('tenant.medicines.edit',$medicine) }}" class="btn btn-sm btn-outline-primary">Edit</a>
<form class="d-inline" method="POST" action="{{ route('tenant.medicines.destroy',$medicine) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
@empty<tr><td colspan="6" class="text-center text-muted">No medicines</td></tr>@endforelse
</tbody></table>{{ $medicines->links() }}</div>
@endsection
BLADE);

w("$base/medicines/create.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Add Medicine')
@section('content')
<h1 class="page-title mb-4">Add Medicine</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.medicines.store') }}">@csrf @include('tenant.medicines._form')
<button class="btn btn-sehat-primary mt-3">Save</button></form></div>
@endsection
BLADE);

w("$base/medicines/edit.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Edit Medicine')
@section('content')
<h1 class="page-title mb-4">Edit Medicine</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.medicines.update',$medicine) }}">@csrf @method('PUT') @include('tenant.medicines._form')
<button class="btn btn-sehat-primary mt-3">Update</button></form></div>
@endsection
BLADE);

w("$base/medicines/_form.blade.php", <<<'BLADE'
@php($medicine=$medicine??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name',$medicine?->name) }}" required></div>
<div class="col-md-3"><label class="form-label">Category</label><select name="medicine_category_id" class="form-select"><option value="">—</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('medicine_category_id',$medicine?->medicine_category_id)==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">Or new category</label><input name="category_name" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Composition</label><input name="composition" class="form-control" value="{{ old('composition',$medicine?->composition) }}"></div>
<div class="col-md-6"><label class="form-label">Manufacturer</label><input name="manufacturer" class="form-control" value="{{ old('manufacturer',$medicine?->manufacturer) }}"></div>
<div class="col-md-3"><label class="form-label">Unit</label><input name="unit" class="form-control" value="{{ old('unit',$medicine?->unit) }}"></div>
<div class="col-md-3"><label class="form-label">Stock</label><input type="number" name="stock" class="form-control" value="{{ old('stock',$medicine?->stock ?? 0) }}"></div>
<div class="col-md-3"><label class="form-label">Price</label><input type="number" step="0.01" name="price" class="form-control" value="{{ old('price',$medicine?->price ?? 0) }}"></div>
<div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$medicine?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$medicine?->status)==='inactive')>Inactive</option></select></div>
</div>
BLADE);

w("$base/prescriptions/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Prescriptions')
@section('content')
<div class="d-flex justify-content-between mb-4"><h1 class="page-title mb-0">Prescriptions</h1><a href="{{ route('tenant.prescriptions.create') }}" class="btn btn-sehat-primary">New Prescription</a></div>
<div class="sehat-card">
<table class="table sehat-table"><thead><tr><th>No</th><th>Patient</th><th>Doctor</th><th>Date</th><th></th></tr></thead><tbody>
@forelse($prescriptions as $prescription)
<tr><td><code>{{ $prescription->prescription_no }}</code></td><td>{{ $prescription->patient?->name }}</td><td>{{ $prescription->doctor?->name }}</td><td>{{ $prescription->prescribed_date->format('d M Y') }}</td>
<td class="text-end"><a href="{{ route('tenant.prescriptions.show',$prescription) }}" class="btn btn-sm btn-outline-secondary">View</a>
<a href="{{ route('tenant.prescriptions.pdf',$prescription) }}" class="btn btn-sm btn-outline-primary">PDF</a></td></tr>
@empty<tr><td colspan="5" class="text-center text-muted">No prescriptions</td></tr>@endforelse
</tbody></table>{{ $prescriptions->links() }}</div>
@endsection
BLADE);

w("$base/prescriptions/create.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Create Prescription')
@section('content')
<h1 class="page-title mb-4">Create Prescription</h1>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.prescriptions.store') }}" id="rxForm">@csrf
<div class="row g-3 mb-3">
<div class="col-md-4"><label class="form-label">Patient</label><select name="patient_id" class="form-select" required><option value="">Select</option>@foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Doctor</label><select name="doctor_id" class="form-select" required><option value="">Select</option>@foreach($doctors as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Date</label><input type="date" name="prescribed_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
<div class="col-md-6"><label class="form-label">Diagnosis</label><input name="diagnosis" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Instructions</label><input name="instructions" class="form-control"></div>
</div>
<h5 class="mb-2">Medicines</h5>
<div id="rxItems"></div>
<button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addRxItem">+ Add medicine</button>
<button class="btn btn-sehat-primary d-block">Save Prescription</button>
</form>
</div>
@push('scripts')
<script>
const medicines = @json($medicines->map(fn($m)=>['id'=>$m->id,'name'=>$m->name]));
function row(i){
  let opts = medicines.map(m=>`<option value="${m.id}">${m.name}</option>`).join('');
  return `<div class="row g-2 mb-2 rx-row"><div class="col-md-3"><select name="items[${i}][medicine_id]" class="form-select" required><option value="">Medicine</option>${opts}</select></div>
  <div class="col-md-2"><input name="items[${i}][dosage]" class="form-control" placeholder="Dosage"></div>
  <div class="col-md-2"><input name="items[${i}][frequency]" class="form-control" placeholder="Frequency"></div>
  <div class="col-md-2"><input name="items[${i}][duration]" class="form-control" placeholder="Duration"></div>
  <div class="col-md-3"><input name="items[${i}][instructions]" class="form-control" placeholder="Instructions"></div></div>`;
}
let i=0; document.getElementById('addRxItem').onclick=()=>{document.getElementById('rxItems').insertAdjacentHTML('beforeend', row(i++));};
document.getElementById('addRxItem').click();
</script>
@endpush
@endsection
BLADE);

w("$base/prescriptions/show.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title',$prescription->prescription_no)
@section('content')
<div class="d-flex justify-content-between mb-4"><div><h1 class="page-title mb-1">{{ $prescription->prescription_no }}</h1>
<p class="text-muted mb-0">{{ $prescription->patient?->name }} · {{ $prescription->doctor?->name }} · {{ $prescription->prescribed_date->format('d M Y') }}</p></div>
<a href="{{ route('tenant.prescriptions.pdf',$prescription) }}" class="btn btn-sehat-primary">Download PDF</a></div>
<div class="sehat-card">
<p><strong>Diagnosis:</strong> {{ $prescription->diagnosis ?: '—' }}</p>
<p><strong>Instructions:</strong> {{ $prescription->instructions ?: '—' }}</p>
<table class="table sehat-table"><thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Instructions</th></tr></thead><tbody>
@foreach($prescription->items as $item)
<tr><td>{{ $item->medicine?->name }}</td><td>{{ $item->dosage }}</td><td>{{ $item->frequency }}</td><td>{{ $item->duration }}</td><td>{{ $item->instructions }}</td></tr>
@endforeach
</tbody></table>
</div>
@endsection
BLADE);

w("$base/prescriptions/pdf.blade.php", <<<'BLADE'
<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>
body{font-family: DejaVu Sans, sans-serif; color:#0B1F3A; font-size:12px;}
h1{color:#0D9488;} table{width:100%; border-collapse:collapse; margin-top:16px;} th,td{border:1px solid #ddd; padding:6px; text-align:left;}
</style></head><body>
<h1>Sehat Prescription</h1>
<p><strong>No:</strong> {{ $prescription->prescription_no }}<br>
<strong>Patient:</strong> {{ $prescription->patient?->name }}<br>
<strong>Doctor:</strong> {{ $prescription->doctor?->name }}<br>
<strong>Date:</strong> {{ $prescription->prescribed_date->format('d M Y') }}</p>
<p><strong>Diagnosis:</strong> {{ $prescription->diagnosis }}</p>
<table><thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Notes</th></tr></thead><tbody>
@foreach($prescription->items as $item)
<tr><td>{{ $item->medicine?->name }}</td><td>{{ $item->dosage }}</td><td>{{ $item->frequency }}</td><td>{{ $item->duration }}</td><td>{{ $item->instructions }}</td></tr>
@endforeach
</tbody></table>
<p style="margin-top:20px"><strong>Instructions:</strong> {{ $prescription->instructions }}</p>
</body></html>
BLADE);

w("$base/medical-histories/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Medical History')
@section('content')
<div class="d-flex justify-content-between mb-4"><h1 class="page-title mb-0">Medical History</h1><a href="{{ route('tenant.medical-histories.create') }}" class="btn btn-sehat-primary">Add Record</a></div>
<div class="sehat-card">
<table class="table sehat-table"><thead><tr><th>Date</th><th>Patient</th><th>Doctor</th><th>Diagnosis</th><th></th></tr></thead><tbody>
@forelse($histories as $history)
<tr><td>{{ $history->visit_date->format('d M Y') }}</td><td>{{ $history->patient?->name }}</td><td>{{ $history->doctor?->name?:'—' }}</td><td>{{ $history->diagnosis }}</td>
<td class="text-end"><a href="{{ route('tenant.medical-histories.edit',$history) }}" class="btn btn-sm btn-outline-primary">Edit</a></td></tr>
@empty<tr><td colspan="5" class="text-center text-muted">No records</td></tr>@endforelse
</tbody></table>{{ $histories->links() }}</div>
@endsection
BLADE);

w("$base/medical-histories/create.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Add Medical History')
@section('content')
<h1 class="page-title mb-4">Add Medical History</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.medical-histories.store') }}" enctype="multipart/form-data">@csrf @include('tenant.medical-histories._form')
<button class="btn btn-sehat-primary mt-3">Save</button></form></div>
@endsection
BLADE);

w("$base/medical-histories/edit.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Edit Medical History')
@section('content')
<h1 class="page-title mb-4">Edit Medical History</h1>
<div class="sehat-card"><form method="POST" action="{{ route('tenant.medical-histories.update',$history) }}">@csrf @method('PUT') @include('tenant.medical-histories._form')
<button class="btn btn-sehat-primary mt-3">Update</button></form></div>
@endsection
BLADE);

w("$base/medical-histories/_form.blade.php", <<<'BLADE'
@php($history=$history??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Patient</label><select name="patient_id" class="form-select" required><option value="">Select</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id',$history?->patient_id ?? $selectedPatient)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Doctor</label><select name="doctor_id" class="form-select"><option value="">—</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id',$history?->doctor_id)==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Visit date</label><input type="date" name="visit_date" class="form-control" value="{{ old('visit_date', optional($history?->visit_date)->format('Y-m-d') ?? date('Y-m-d')) }}" required></div>
<div class="col-md-8"><label class="form-label">Diagnosis</label><input name="diagnosis" class="form-control" value="{{ old('diagnosis',$history?->diagnosis) }}"></div>
<div class="col-12"><label class="form-label">Treatment details</label><textarea name="treatment_details" class="form-control" rows="3">{{ old('treatment_details',$history?->treatment_details) }}</textarea></div>
<div class="col-12"><label class="form-label">Allergies</label><textarea name="allergies" class="form-control" rows="2">{{ old('allergies',$history?->allergies) }}</textarea></div>
<div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$history?->notes) }}</textarea></div>
@if(!isset($history))
<div class="col-md-6"><label class="form-label">Upload report</label><input type="file" name="document" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Document title</label><input name="document_title" class="form-control"></div>
@endif
</div>
BLADE);

w("$base/reports/index.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Reports')
@section('content')
<h1 class="page-title mb-4">Reports</h1>
<div class="sehat-card mb-3">
<form class="row g-2" method="GET">
<div class="col-md-3"><input type="date" name="from" value="{{ $from }}" class="form-control"></div>
<div class="col-md-3"><input type="date" name="to" value="{{ $to }}" class="form-control"></div>
<div class="col-md-2"><button class="btn btn-sehat-teal">Apply</button></div>
</form>
</div>
<div class="row g-3 mb-4">
<div class="col-md-4"><div class="sehat-stat"><div class="stat-label">Patients</div><div class="stat-value">{{ $summary['patients'] }}</div></div></div>
<div class="col-md-4"><div class="sehat-stat"><div class="stat-label">Appointments</div><div class="stat-value">{{ $summary['appointments'] }}</div></div></div>
<div class="col-md-4"><div class="sehat-stat"><div class="stat-label">Prescriptions</div><div class="stat-value">{{ $summary['prescriptions'] }}</div></div></div>
</div>
<div class="sehat-card">
<h5 class="mb-3">Doctor-wise appointments</h5>
<table class="table sehat-table"><thead><tr><th>Doctor</th><th>Appointments</th></tr></thead><tbody>
@foreach($doctorWise as $doctor)
<tr><td>{{ $doctor->name }}</td><td>{{ $doctor->appointments_count }}</td></tr>
@endforeach
</tbody></table>
</div>
@endsection
BLADE);

w("$base/profile/edit.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Profile')
@section('content')
<h1 class="page-title mb-4">My Profile</h1>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.profile.update') }}">@csrf @method('PUT')
<div class="row g-3">
<div class="col-md-6"><label class="form-label">First name</label><input name="first_name" class="form-control" value="{{ old('first_name',$user->first_name) }}" required></div>
<div class="col-md-6"><label class="form-label">Last name</label><input name="last_name" class="form-control" value="{{ old('last_name',$user->last_name) }}" required></div>
<div class="col-md-6"><label class="form-label">Email</label><input class="form-control" value="{{ $user->email }}" disabled></div>
<div class="col-md-6"><label class="form-label">Mobile</label><input name="mobile" class="form-control" value="{{ old('mobile',$user->mobile) }}"></div>
<div class="col-md-6"><label class="form-label">Subscription ID</label><input class="form-control" value="{{ $user->subscription_id }}" disabled></div>
</div>
<button class="btn btn-sehat-primary mt-3">Update Profile</button>
<a href="{{ route('tenant.password.change') }}" class="btn btn-outline-secondary mt-3">Change Password</a>
</form>
</div>
@endsection
BLADE);

w("$base/profile/change-password.blade.php", <<<'BLADE'
@extends('layouts.tenant')
@section('title','Change Password')
@section('content')
<h1 class="page-title mb-4">Change Password</h1>
<div class="sehat-card">
<form method="POST" action="{{ route('tenant.password.change.update') }}">@csrf @method('PUT')
<div class="mb-3"><label class="form-label">Current password</label><input type="password" name="current_password" class="form-control" required></div>
<div class="mb-3"><label class="form-label">New password</label><input type="password" name="password" class="form-control" required><div class="form-text">Min 8 chars with upper, lower, number, symbol</div></div>
<div class="mb-3"><label class="form-label">Confirm password</label><input type="password" name="password_confirmation" class="form-control" required></div>
<button class="btn btn-sehat-primary">Update Password</button>
</form>
</div>
@endsection
BLADE);

w("$emails/tenant-credentials.blade.php", <<<'BLADE'
<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;color:#0B1F3A;">
<h2>Welcome to Sehat</h2>
<p>Your clinic portal is ready.</p>
<p><strong>Clinic:</strong> {{ $customer->clinic_name }}<br>
<strong>Tenant code:</strong> {{ $customer->tenant_code }}<br>
<strong>Email:</strong> {{ $customer->email }}<br>
<strong>Password:</strong> {{ $plainPassword }}</p>
<p>Login at: {{ url('/clinic/login') }}</p>
</body></html>
BLADE);

echo "Done clinical views\n";
