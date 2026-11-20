@extends('layouts.tenant')
@section('title','Doctors')
@section('page_title', 'Doctors')
@section('content')
<div class="sehat-page-header"><div><h1 class="page-title">Doctors</h1></div><a href="{{ route('tenant.doctors.create') }}" class="btn btn-sehat-primary">Add Doctor</a></div>
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