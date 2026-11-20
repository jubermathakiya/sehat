@php($doctor=$doctor??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name',$doctor?->name) }}" required></div>
<div class="col-md-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$doctor?->email) }}"></div>
<div class="col-md-3"><label class="form-label">Mobile</label><input name="mobile" class="form-control" value="{{ old('mobile',$doctor?->mobile) }}"></div>
<div class="col-md-6"><label class="form-label">Specialization</label>
<select name="specialization_id" class="form-select"><option value="">—</option>@foreach($specializations as $s)<option value="{{ $s->id }}" @selected(old('specialization_id',$doctor?->specialization_id)==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Or new specialization</label><input name="specialization_name" class="form-control" value="{{ old('specialization_name') }}"></div>
<div class="col-12"><label class="form-label">Qualification</label><input name="qualification" class="form-control" value="{{ old('qualification',$doctor?->qualification) }}"></div>
<div class="col-12"><label class="form-label">Bio</label><textarea name="bio" class="form-control" rows="2">{{ old('bio',$doctor?->bio) }}</textarea></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$doctor?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$doctor?->status)==='inactive')>Inactive</option></select></div>
</div>