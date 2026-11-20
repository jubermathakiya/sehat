@extends('layouts.tenant')
@section('title','Appointments')
@section('page_title', 'Appointments')
@section('content')
<div class="sehat-page-header"><div><h1 class="page-title">Appointments</h1></div><a href="{{ route('tenant.appointments.create') }}" class="btn btn-sehat-primary">Book Appointment</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET">
<div class="col-md-3"><input type="date" name="date" value="{{ request('date') }}" class="form-control"></div>
<div class="col-md-3"><select name="doctor_id" class="form-select"><option value="">All doctors</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(request('doctor_id')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-2"><button class="btn btn-sehat-primary">Filter</button></div>
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