@php($plan = $plan ?? null)
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name',$plan?->name) }}" required></div>
    <div class="col-md-6"><label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" @selected(old('status',$plan?->status)==='active')>Active</option>
            <option value="inactive" @selected(old('status',$plan?->status)==='inactive')>Inactive</option>
        </select>
    </div>
    <div class="col-md-3"><label class="form-label">Monthly</label><input type="number" step="0.01" name="monthly_price" class="form-control" value="{{ old('monthly_price',$plan?->monthly_price ?? 0) }}" required></div>
    <div class="col-md-3"><label class="form-label">Quarterly</label><input type="number" step="0.01" name="quarterly_price" class="form-control" value="{{ old('quarterly_price',$plan?->quarterly_price ?? 0) }}" required></div>
    <div class="col-md-3"><label class="form-label">Half-yearly</label><input type="number" step="0.01" name="half_yearly_price" class="form-control" value="{{ old('half_yearly_price',$plan?->half_yearly_price ?? 0) }}" required></div>
    <div class="col-md-3"><label class="form-label">Yearly</label><input type="number" step="0.01" name="yearly_price" class="form-control" value="{{ old('yearly_price',$plan?->yearly_price ?? 0) }}" required></div>
    <div class="col-md-3"><label class="form-label">Trial days</label><input type="number" name="trial_days" class="form-control" value="{{ old('trial_days',$plan?->trial_days ?? 14) }}"></div>
    <div class="col-md-3"><label class="form-label">Max doctors</label><input type="number" name="max_doctors" class="form-control" value="{{ old('max_doctors',$plan?->max_doctors ?? 1) }}" required></div>
    <div class="col-md-3"><label class="form-label">Max staff</label><input type="number" name="max_staff" class="form-control" value="{{ old('max_staff',$plan?->max_staff ?? 5) }}" required></div>
    <div class="col-md-3"><label class="form-label">Max patients</label><input type="number" name="max_patients" class="form-control" value="{{ old('max_patients',$plan?->max_patients) }}"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description',$plan?->description) }}</textarea></div>
</div>

