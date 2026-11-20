<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Appointment;
use App\Models\Tenant\Patient;
use App\Models\Tenant\Prescription;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'upcoming_appointments' => Appointment::whereDate('appointment_date', '>', today())
                ->where('status', 'scheduled')->count(),
            'total_prescriptions' => Prescription::count(),
        ];

        $recentPatients = Patient::latest()->take(8)->get();
        $todayAppointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        return view('tenant.dashboard.index', compact('stats', 'recentPatients', 'todayAppointments'));
    }
}
