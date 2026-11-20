@php($history=$history??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Patient</label><select name="patient_id" class="form-select" required><option value="">Select</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id',$history?->patient_id ?? ($selectedPatient ?? null))==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
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