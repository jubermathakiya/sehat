<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Doctor;
use App\Models\Tenant\Specialization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('specialization')->latest();
        if ($q = $request->get('q')) {
            $query->where('name', 'like', "%{$q}%");
        }
        $doctors = $query->paginate(15)->withQueryString();

        return view('tenant.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specializations = Specialization::orderBy('name')->get();

        return view('tenant.doctors.create', compact('specializations'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if (! empty($data['specialization_name'])) {
            $spec = Specialization::firstOrCreate(['name' => $data['specialization_name']]);
            $data['specialization_id'] = $spec->id;
        }
        unset($data['specialization_name']);

        $doctor = Doctor::create($data);

        if ($request->filled('schedules')) {
            foreach ($request->input('schedules', []) as $schedule) {
                if (! empty($schedule['day_of_week']) && ! empty($schedule['start_time']) && ! empty($schedule['end_time'])) {
                    $doctor->schedules()->create($schedule);
                }
            }
        }

        return redirect()->route('tenant.doctors.index')->with('success', 'Doctor added.');
    }

    public function edit(Doctor $doctor)
    {
        $specializations = Specialization::orderBy('name')->get();
        $doctor->load('schedules');

        return view('tenant.doctors.edit', compact('doctor', 'specializations'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $this->validated($request);
        if (! empty($data['specialization_name'])) {
            $spec = Specialization::firstOrCreate(['name' => $data['specialization_name']]);
            $data['specialization_id'] = $spec->id;
        }
        unset($data['specialization_name']);
        $doctor->update($data);

        return redirect()->route('tenant.doctors.index')->with('success', 'Doctor updated.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()->route('tenant.doctors.index')->with('success', 'Doctor deleted.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'specialization_id' => ['nullable', 'exists:tenant.specializations,id'],
            'specialization_name' => ['nullable', 'string', 'max:255'],
            'qualification' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
