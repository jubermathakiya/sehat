@extends('layouts.tenant')
@section('title',$prescription->prescription_no)
@section('page_title', $prescription->prescription_no)
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