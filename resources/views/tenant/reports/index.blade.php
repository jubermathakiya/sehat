@extends('layouts.tenant')
@section('title','Reports')
@section('page_title', 'Reports')
@section('content')
<h1 class="page-title mb-4">Reports</h1>
<div class="sehat-card mb-3">
<form class="row g-2" method="GET">
<div class="col-md-3"><input type="date" name="from" value="{{ $from }}" class="form-control"></div>
<div class="col-md-3"><input type="date" name="to" value="{{ $to }}" class="form-control"></div>
<div class="col-md-2"><button class="btn btn-sehat-primary">Apply</button></div>
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