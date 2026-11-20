<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Medicine;
use App\Models\Tenant\MedicineCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('category')->latest();
        if ($q = $request->get('q')) {
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('composition', 'like', "%{$q}%")
                    ->orWhere('manufacturer', 'like', "%{$q}%");
            });
        }
        $medicines = $query->paginate(15)->withQueryString();

        return view('tenant.medicines.index', compact('medicines'));
    }

    public function create()
    {
        $categories = MedicineCategory::orderBy('name')->get();

        return view('tenant.medicines.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if (! empty($data['category_name'])) {
            $cat = MedicineCategory::firstOrCreate(['name' => $data['category_name']]);
            $data['medicine_category_id'] = $cat->id;
        }
        unset($data['category_name']);
        Medicine::create($data);

        return redirect()->route('tenant.medicines.index')->with('success', 'Medicine added.');
    }

    public function edit(Medicine $medicine)
    {
        $categories = MedicineCategory::orderBy('name')->get();

        return view('tenant.medicines.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $data = $this->validated($request);
        if (! empty($data['category_name'])) {
            $cat = MedicineCategory::firstOrCreate(['name' => $data['category_name']]);
            $data['medicine_category_id'] = $cat->id;
        }
        unset($data['category_name']);
        $medicine->update($data);

        return redirect()->route('tenant.medicines.index')->with('success', 'Medicine updated.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('tenant.medicines.index')->with('success', 'Medicine deleted.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'medicine_category_id' => ['nullable', 'exists:tenant.medicine_categories,id'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'composition' => ['nullable', 'string', 'max:255'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
