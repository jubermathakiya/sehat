@php($company=$company??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Company name</label><input name="name" class="form-control" value="{{ old('name',$company?->name) }}" required></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$company?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$company?->status)==='inactive')>Inactive</option></select></div>
<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$company?->address) }}</textarea></div>
<div class="col-md-6"><label class="form-label">Logo</label><input type="file" name="logo" class="form-control" accept="image/*"></div>
<div class="col-md-6"><label class="form-label">Remark</label><input name="remark" class="form-control" value="{{ old('remark',$company?->remark) }}"></div>
</div>
