<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Appointment;
use App\Models\Tenant\Doctor;
use App\Models\Tenant\Patient;
use App\Models\Tenant\Prescription;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->toDateString());

        $summary = [
            'patients' => Patient::whereBetween('created_at', [$from, $to.' 23:59:59'])->count(),
            'appointments' => Appointment::whereBetween('appointment_date', [$from, $to])->count(),
            'prescriptions' => Prescription::whereBetween('prescribed_date', [$from, $to])->count(),
        ];

        $doctorWise = Doctor::withCount([
            'appointments' => fn ($q) => $q->whereBetween('appointment_date', [$from, $to]),
        ])->orderBy('name')->get();

        return view('tenant.reports.index', compact('summary', 'doctorWise', 'from', 'to'));
    }
}
