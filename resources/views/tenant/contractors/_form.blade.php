@php($contractor=$contractor??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name',$contractor?->name) }}" required></div>
<div class="col-md-3"><label class="form-label">Mobile</label><input name="mobile" class="form-control" value="{{ old('mobile',$contractor?->mobile) }}" required></div>
<div class="col-md-3"><label class="form-label">Alternate mobile</label><input name="alternate_mobile" class="form-control" value="{{ old('alternate_mobile',$contractor?->alternate_mobile) }}"></div>
<div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$contractor?->email) }}"></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$contractor?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$contractor?->status)==='inactive')>Inactive</option></select></div>
<div class="col-12"><label class="form-label">Associated companies</label>
<select name="companies[]" class="form-select" multiple required size="5">
@foreach($companies as $company)
<option value="{{ $company->id }}" @selected(collect(old('companies',$contractor?->companies?->pluck('id')->all()??[]))->contains($company->id))>{{ $company->name }}</option>
@endforeach
</select></div>
<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$contractor?->address) }}</textarea></div>
<div class="col-12"><label class="form-label">Remarks</label><textarea name="remarks" class="form-control" rows="2">{{ old('remarks',$contractor?->remarks) }}</textarea></div>
</div>
