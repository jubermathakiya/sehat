<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Appointment;
use App\Models\Tenant\Doctor;
use App\Models\Tenant\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor'])->latest('appointment_date');

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(20)->withQueryString();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();

        return view('tenant.appointments.index', compact('appointments', 'doctors'));
    }

    public function create()
    {
        $patients = Patient::where('status', 'active')->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();

        return view('tenant.appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        Appointment::create($this->validated($request));

        return redirect()->route('tenant.appointments.index')->with('success', 'Appointment booked.');
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::where('status', 'active')->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();

        return view('tenant.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($this->validated($request));

        return redirect()->route('tenant.appointments.index')->with('success', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('tenant.appointments.index')->with('success', 'Appointment cancelled.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'patient_id' => ['required', 'exists:tenant.patients,id'],
            'doctor_id' => ['required', 'exists:tenant.doctors,id'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required'],
            'status' => ['required', Rule::in(['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'])],
            'reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
