@extends('layouts.tenant')

@section('title', 'Dashboard')
@section('page_title', 'Clinic Dashboard')

@section('content')
<div class="sehat-stat-grid mb-4">
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon teal"><i class="bi bi-people"></i></div>
        <div class="sehat-stat-label">Patients</div>
        <div class="sehat-stat-value">{{ $stats['total_patients'] ?? 0 }}</div>
    </div>
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon orange"><i class="bi bi-calendar-event"></i></div>
        <div class="sehat-stat-label">Today Appointments</div>
        <div class="sehat-stat-value">{{ $stats['today_appointments'] ?? 0 }}</div>
    </div>
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon navy"><i class="bi bi-file-medical"></i></div>
        <div class="sehat-stat-label">Prescriptions (month)</div>
        <div class="sehat-stat-value">{{ $stats['month_prescriptions'] ?? 0 }}</div>
    </div>
    <div class="sehat-stat-card">
        <div class="sehat-stat-icon teal"><i class="bi bi-stethoscope"></i></div>
        <div class="sehat-stat-label">Doctors</div>
        <div class="sehat-stat-value">{{ $stats['total_doctors'] ?? 0 }}</div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="sehat-card">
            <div class="sehat-card-header">Recent Patients</div>
            <div class="sehat-card-body p-0">
                <div class="sehat-table-wrap">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Code</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPatients ?? [] as $patient)
                                <tr>
                                    <td>
                                        <a href="{{ route('tenant.patients.show', $patient) }}" class="fw-semibold text-sehat-navy text-decoration-none">{{ $patient->name }}</a>
                                    </td>
                                    <td><code>{{ $patient->patient_code }}</code></td>
                                    <td class="text-muted small">{{ $patient->mobile ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No patients yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="sehat-card">
            <div class="sehat-card-header">Today Appointments</div>
            <div class="sehat-card-body p-0">
                <div class="sehat-table-wrap">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayAppointments ?? [] as $appointment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                    <td>{{ $appointment->patient->name ?? '—' }}</td>
                                    <td>{{ $appointment->doctor->name ?? '—' }}</td>
                                    <td>@include('partials.sehat-status-badge', ['status' => $appointment->status])</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No appointments today.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


