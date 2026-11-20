<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Doctor;
use App\Models\Tenant\MedicalHistory;
use App\Models\Tenant\Patient;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalHistory::with(['patient', 'doctor'])->latest('visit_date');
        if ($q = $request->get('q')) {
            $query->whereHas('patient', fn ($p) => $p->where('name', 'like', "%{$q}%"))
                ->orWhere('diagnosis', 'like', "%{$q}%");
        }
        $histories = $query->paginate(15)->withQueryString();

        return view('tenant.medical-histories.index', compact('histories'));
    }

    public function create(Request $request)
    {
        $patients = Patient::where('status', 'active')->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        $selectedPatient = $request->get('patient_id');

        return view('tenant.medical-histories.create', compact('patients', 'doctors', 'selectedPatient'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:tenant.patients,id'],
            'doctor_id' => ['nullable', 'exists:tenant.doctors,id'],
            'visit_date' => ['required', 'date'],
            'diagnosis' => ['nullable', 'string'],
            'treatment_details' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'document' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
            'document_title' => ['nullable', 'string', 'max:255'],
        ]);

        $history = MedicalHistory::create(collect($data)->except(['document', 'document_title'])->all());

        if ($request->hasFile('document')) {
            $tenantId = session('tenant_customer_id');
            $path = $request->file('document')->store("tenants/{$tenantId}/documents", 'public');
            $history->documents()->create([
                'patient_id' => $history->patient_id,
                'title' => $data['document_title'] ?? 'Medical Report',
                'file_path' => $path,
                'file_type' => $request->file('document')->getClientMimeType(),
            ]);
        }

        return redirect()->route('tenant.medical-histories.index')->with('success', 'Medical history saved.');
    }

    public function edit(MedicalHistory $medical_history)
    {
        $patients = Patient::where('status', 'active')->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();

        return view('tenant.medical-histories.edit', [
            'history' => $medical_history,
            'patients' => $patients,
            'doctors' => $doctors,
        ]);
    }

    public function update(Request $request, MedicalHistory $medical_history)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:tenant.patients,id'],
            'doctor_id' => ['nullable', 'exists:tenant.doctors,id'],
            'visit_date' => ['required', 'date'],
            'diagnosis' => ['nullable', 'string'],
            'treatment_details' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $medical_history->update($data);

        return redirect()->route('tenant.medical-histories.index')->with('success', 'Medical history updated.');
    }

    public function destroy(MedicalHistory $medical_history)
    {
        $medical_history->delete();

        return redirect()->route('tenant.medical-histories.index')->with('success', 'Record deleted.');
    }
}
