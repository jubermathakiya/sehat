<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Company;
use App\Models\Tenant\Contractor;
use App\Support\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContractorController extends Controller
{
    public function index(Request $request)
    {
        $query = Contractor::with('companies')->latest();

        if ($q = $request->get('q')) {
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('contractor_code', 'like', "%{$q}%")
                    ->orWhere('mobile', 'like', "%{$q}%");
            });
        }

        if ($companyId = $request->get('company_id')) {
            $query->whereHas('companies', fn ($q) => $q->where('companies.id', $companyId));
        }

        $contractors = $query->paginate(15)->withQueryString();
        $companies = Company::where('status', 'active')->orderBy('name')->get();

        return view('tenant.contractors.index', compact('contractors', 'companies'));
    }

    public function create()
    {
        $companies = Company::where('status', 'active')->orderBy('name')->get();

        return view('tenant.contractors.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'alternate_mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'remarks' => ['nullable', 'string'],
            'companies' => ['required', 'array', 'min:1'],
            'companies.*' => ['exists:tenant.companies,id'],
        ]);

        $contractor = Contractor::create([
            ...collect($data)->except('companies')->all(),
            'contractor_code' => CodeGenerator::uniqueFourDigit(Contractor::class),
        ]);

        $contractor->companies()->sync($data['companies']);

        return redirect()->route('tenant.contractors.index')->with('success', 'Contractor created.');
    }

    public function edit(Contractor $contractor)
    {
        $companies = Company::where('status', 'active')->orderBy('name')->get();
        $contractor->load('companies');

        return view('tenant.contractors.edit', compact('contractor', 'companies'));
    }

    public function update(Request $request, Contractor $contractor)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'alternate_mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'remarks' => ['nullable', 'string'],
            'companies' => ['required', 'array', 'min:1'],
            'companies.*' => ['exists:tenant.companies,id'],
        ]);

        $contractor->update(collect($data)->except('companies')->all());
        $contractor->companies()->sync($data['companies']);

        return redirect()->route('tenant.contractors.index')->with('success', 'Contractor updated.');
    }

    public function destroy(Contractor $contractor)
    {
        $contractor->delete();

        return redirect()->route('tenant.contractors.index')->with('success', 'Contractor deleted.');
    }
}
