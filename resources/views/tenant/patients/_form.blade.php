@php($patient=$patient??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Patient name</label><input name="name" class="form-control" value="{{ old('name',$patient?->name) }}" required></div>
<div class="col-md-3"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">—</option>@foreach(['male','female','other'] as $g)<option value="{{ $g }}" @selected(old('gender',$patient?->gender)===$g)>{{ ucfirst($g) }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">Age</label><input type="number" name="age" class="form-control" value="{{ old('age',$patient?->age) }}"></div>
<div class="col-md-4"><label class="form-label">Mobile</label><input name="mobile" class="form-control" value="{{ old('mobile',$patient?->mobile) }}"></div>
<div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$patient?->email) }}"></div>
<div class="col-md-4"><label class="form-label">Blood group</label><input name="blood_group" class="form-control" value="{{ old('blood_group',$patient?->blood_group) }}"></div>
<div class="col-md-6"><label class="form-label">Contractor</label>
<select name="contractor_id" class="form-select"><option value="">—</option>
@foreach($contractors as $contractor)
<option value="{{ $contractor->id }}" @selected(old('contractor_id',$patient?->contractor_id)==$contractor->id)>{{ $contractor->name }} ({{ $contractor->companies->pluck('name')->join(', ') }})</option>
@endforeach
</select></div>
<div class="col-md-3"><label class="form-label">Charges</label><input type="number" step="0.01" name="charges" class="form-control" value="{{ old('charges',$patient?->charges ?? 0) }}"></div>
<div class="col-md-3"><label class="form-label">Date</label><input type="date" name="registration_date" class="form-control" value="{{ old('registration_date', optional($patient?->registration_date)->format('Y-m-d') ?? date('Y-m-d')) }}"></div>
<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$patient?->address) }}</textarea></div>
<div class="col-12"><label class="form-label">Allergies</label><textarea name="allergies" class="form-control" rows="2">{{ old('allergies',$patient?->allergies) }}</textarea></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$patient?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$patient?->status)==='inactive')>Inactive</option></select></div>
<div class="col-md-6"><label class="form-label">Notes</label><input name="notes" class="form-control" value="{{ old('notes',$patient?->notes) }}"></div>
</div>
