@php($medicine=$medicine??null)
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name',$medicine?->name) }}" required></div>
<div class="col-md-3"><label class="form-label">Category</label><select name="medicine_category_id" class="form-select"><option value="">—</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('medicine_category_id',$medicine?->medicine_category_id)==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">Or new category</label><input name="category_name" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Composition</label><input name="composition" class="form-control" value="{{ old('composition',$medicine?->composition) }}"></div>
<div class="col-md-6"><label class="form-label">Manufacturer</label><input name="manufacturer" class="form-control" value="{{ old('manufacturer',$medicine?->manufacturer) }}"></div>
<div class="col-md-3"><label class="form-label">Unit</label><input name="unit" class="form-control" value="{{ old('unit',$medicine?->unit) }}"></div>
<div class="col-md-3"><label class="form-label">Stock</label><input type="number" name="stock" class="form-control" value="{{ old('stock',$medicine?->stock ?? 0) }}"></div>
<div class="col-md-3"><label class="form-label">Price</label><input type="number" step="0.01" name="price" class="form-control" value="{{ old('price',$medicine?->price ?? 0) }}"></div>
<div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" @selected(old('status',$medicine?->status??'active')==='active')>Active</option><option value="inactive" @selected(old('status',$medicine?->status)==='inactive')>Inactive</option></select></div>
</div>