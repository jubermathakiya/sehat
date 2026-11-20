<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Contractor;
use App\Models\Tenant\Patient;
use App\Support\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with(['contractor', 'parent'])->whereNull('parent_patient_id')->latest();

        if ($q = $request->get('q')) {
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('patient_code', 'like', "%{$q}%")
                    ->orWhere('mobile', 'like', "%{$q}%");
            });
        }

        $patients = $query->paginate(15)->withQueryString();

        return view('tenant.patients.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $contractors = Contractor::with('companies')->where('status', 'active')->orderBy('name')->get();
        $parent = $request->get('parent_id') ? Patient::find($request->get('parent_id')) : null;

        return view('tenant.patients.create', compact('contractors', 'parent'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['patient_code'] = CodeGenerator::patientCode();
        $data['registration_date'] = $data['registration_date'] ?? now()->toDateString();

        $patient = Patient::create($data);

        return redirect()->route('tenant.patients.show', $patient)->with('success', 'Patient registered.');
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'contractor.companies', 'familyMembers', 'parent',
            'appointments.doctor', 'prescriptions', 'medicalHistories.doctor', 'documents',
        ]);

        return view('tenant.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $contractors = Contractor::with('companies')->where('status', 'active')->orderBy('name')->get();

        return view('tenant.patients.edit', compact('patient', 'contractors'));
    }

    public function update(Request $request, Patient $patient)
    {
        $patient->update($this->validated($request));

        return redirect()->route('tenant.patients.show', $patient)->with('success', 'Patient updated.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('tenant.patients.index')->with('success', 'Patient deleted.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'allergies' => ['nullable', 'string'],
            'contractor_id' => ['nullable', 'exists:tenant.contractors,id'],
            'charges' => ['nullable', 'numeric', 'min:0'],
            'registration_date' => ['nullable', 'date'],
            'parent_patient_id' => ['nullable', 'exists:tenant.patients,id'],
            'family_relation' => ['nullable', Rule::in(['self', 'spouse', 'child', 'parent', 'sibling', 'other'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
