<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::withCount('contractors')->latest();
        if ($q = $request->get('q')) {
            $query->where('name', 'like', "%{$q}%");
        }
        $companies = $query->paginate(15)->withQueryString();

        return view('tenant.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('tenant.companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'remark' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('logo')) {
            $tenantId = session('tenant_customer_id');
            $data['logo'] = $request->file('logo')->store("tenants/{$tenantId}/logos", 'public');
        }

        Company::create($data);

        return redirect()->route('tenant.companies.index')->with('success', 'Company created.');
    }

    public function edit(Company $company)
    {
        return view('tenant.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'remark' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('logo')) {
            $tenantId = session('tenant_customer_id');
            $data['logo'] = $request->file('logo')->store("tenants/{$tenantId}/logos", 'public');
        }

        $company->update($data);

        return redirect()->route('tenant.companies.index')->with('success', 'Company updated.');
    }

    public function destroy(Company $company)
    {
        if ($company->contractors()->exists()) {
            return back()->with('error', 'Cannot delete company with associated contractors.');
        }

        $company->delete();

        return redirect()->route('tenant.companies.index')->with('success', 'Company deleted.');
    }
}
