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