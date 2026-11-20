@extends('layouts.tenant')
@section('title','Create Prescription')
@section('page_title', 'Create Prescription')
@section('content')
<div class="sehat-page-header">
    <div>
        <h1 class="page-title">Create Prescription</h1>
    </div>
</div>
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