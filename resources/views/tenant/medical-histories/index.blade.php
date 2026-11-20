@extends('layouts.tenant')
@section('title','Medical History')
@section('page_title', 'Medical History')
@section('content')
<div class="sehat-page-header"><div><h1 class="page-title">Medical History</h1></div><a href="{{ route('tenant.medical-histories.create') }}" class="btn btn-sehat-primary">Add Record</a></div>
<div class="sehat-card">
<table class="table sehat-table"><thead><tr><th>Date</th><th>Patient</th><th>Doctor</th><th>Diagnosis</th><th></th></tr></thead><tbody>
@forelse($histories as $history)
<tr><td>{{ $history->visit_date->format('d M Y') }}</td><td>{{ $history->patient?->name }}</td><td>{{ $history->doctor?->name?:'—' }}</td><td>{{ $history->diagnosis }}</td>
<td class="text-end"><a href="{{ route('tenant.medical-histories.edit',$history) }}" class="btn btn-sm btn-outline-primary">Edit</a></td></tr>
@empty<tr><td colspan="5" class="text-center text-muted">No records</td></tr>@endforelse
</tbody></table>{{ $histories->links() }}</div>
@endsection