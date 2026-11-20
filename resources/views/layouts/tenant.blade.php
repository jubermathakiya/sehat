<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Clinic Portal') � {{ $currentTenant->clinic_name ?? 'Sehat' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/sehat-theme.css') }}?v=3" rel="stylesheet">
    @stack('styles')
</head>
<body class="sehat-app">
<div class="sehat-layout">
    <aside class="sehat-sidebar" id="sehatSidebar">
        <div class="sehat-sidebar-brand">
            <div class="logo-mark"><i class="bi bi-hospital"></i></div>
            <div class="min-w-0">
                <div class="brand-text text-truncate">{{ $currentTenant->clinic_name ?? 'Clinic Portal' }}</div>
                <div class="brand-sub">Doctor Management</div>
            </div>
        </div>
        <nav class="sehat-nav">
            <div class="sehat-nav-section">Overview</div>
            <a href="{{ route('tenant.dashboard') }}" class="sehat-nav-link {{ request()->routeIs('tenant.dashboard') || request()->is('clinic') || request()->is('clinic/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <div class="sehat-nav-section">Operations</div>
            <a href="{{ route('tenant.companies.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.companies.*') ? 'active' : '' }}">
                <i class="bi bi-buildings"></i> Companies
            </a>
            <a href="{{ route('tenant.contractors.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.contractors.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Contractors
            </a>
            <a href="{{ route('tenant.patients.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.patients.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Patients
            </a>
            <a href="{{ route('tenant.doctors.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.doctors.*') ? 'active' : '' }}">
                <i class="bi bi-stethoscope"></i> Doctors
            </a>
            <a href="{{ route('tenant.appointments.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.appointments.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Appointments
            </a>
            <div class="sehat-nav-section">Pharmacy & Records</div>
            <a href="{{ route('tenant.medicines.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.medicines.*') ? 'active' : '' }}">
                <i class="bi bi-capsule"></i> Medicines
            </a>
            <a href="{{ route('tenant.prescriptions.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.prescriptions.*') ? 'active' : '' }}">
                <i class="bi bi-file-medical"></i> Prescriptions
            </a>
            <a href="{{ route('tenant.medical-histories.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.medical-histories.*') ? 'active' : '' }}">
                <i class="bi bi-journal-medical"></i> Medical History
            </a>
            <a href="{{ route('tenant.reports.index') }}" class="sehat-nav-link {{ request()->routeIs('tenant.reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Reports
            </a>
            <div class="sehat-nav-section">Account</div>
            <a href="{{ route('tenant.profile.edit') }}" class="sehat-nav-link {{ request()->routeIs('tenant.profile.*') || request()->routeIs('tenant.password.change*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
        </nav>
        <div class="p-3 border-top border-light border-opacity-10">
            <form action="{{ route('tenant.logout') }}" method="POST">
                @csrf
                <button type="submit" class="sehat-nav-link w-100 text-start">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="sehat-main">
        <header class="sehat-topbar">
            <div class="d-flex align-items-center gap-2 min-w-0">
                <button type="button" class="btn btn-sm btn-sehat-ghost d-lg-none" data-sehat-sidebar-toggle aria-label="Toggle menu">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="sehat-topbar-title text-truncate">
                    @hasSection('page_title')
                        @yield('page_title')
                    @else
                        @yield('title', 'Dashboard')
                    @endif
                </h1>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted small flex-shrink-0">
                <i class="bi bi-person text-sehat-orange"></i>
                <span>{{ auth('tenant')->user()->first_name ?? '' }} {{ auth('tenant')->user()->last_name ?? '' }}</span>
            </div>
        </header>

        <main class="sehat-content sehat-fade-in">
            @if(!empty($subscriptionGraceWarning))
                <div class="sehat-grace-banner">
                    <i class="bi bi-exclamation-triangle-fill text-sehat-teal"></i>
                    <span>{{ $subscriptionGraceWarning }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="alert sehat-alert sehat-alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert sehat-alert sehat-alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(isset($errors) && $errors->any())
                <div class="alert sehat-alert sehat-alert-danger" role="alert">
                    <strong>Please fix the following:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/sehat-app.js') }}"></script>
@stack('scripts')
</body>
</html>
