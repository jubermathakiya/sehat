<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Doctor;
use App\Models\Tenant\Medicine;
use App\Models\Tenant\Patient;
use App\Models\Tenant\Prescription;
use App\Support\CodeGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::with(['patient', 'doctor'])->latest();
        if ($q = $request->get('q')) {
            $query->where('prescription_no', 'like', "%{$q}%")
                ->orWhereHas('patient', fn ($p) => $p->where('name', 'like', "%{$q}%"));
        }
        $prescriptions = $query->paginate(15)->withQueryString();

        return view('tenant.prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $patients = Patient::where('status', 'active')->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        $medicines = Medicine::where('status', 'active')->orderBy('name')->get();

        return view('tenant.prescriptions.create', compact('patients', 'doctors', 'medicines'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:tenant.patients,id'],
            'doctor_id' => ['required', 'exists:tenant.doctors,id'],
            'prescribed_date' => ['required', 'date'],
            'diagnosis' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', 'exists:tenant.medicines,id'],
            'items.*.dosage' => ['nullable', 'string'],
            'items.*.frequency' => ['nullable', 'string'],
            'items.*.duration' => ['nullable', 'string'],
            'items.*.instructions' => ['nullable', 'string'],
        ]);

        $prescription = DB::connection('tenant')->transaction(function () use ($data) {
            $prescription = Prescription::create([
                'prescription_no' => CodeGenerator::prescriptionNo(),
                'patient_id' => $data['patient_id'],
                'doctor_id' => $data['doctor_id'],
                'prescribed_date' => $data['prescribed_date'],
                'diagnosis' => $data['diagnosis'] ?? null,
                'instructions' => $data['instructions'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $prescription->items()->create($item);
            }

            return $prescription;
        });

        return redirect()->route('tenant.prescriptions.show', $prescription)->with('success', 'Prescription created.');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'items.medicine']);

        return view('tenant.prescriptions.show', compact('prescription'));
    }

    public function pdf(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'items.medicine']);
        $pdf = Pdf::loadView('tenant.prescriptions.pdf', compact('prescription'));

        return $pdf->download($prescription->prescription_no.'.pdf');
    }
}
