<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') � Sehat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/sehat-theme.css') }}?v=3" rel="stylesheet">
    @stack('styles')
</head>
<body class="sehat-app">
<div class="sehat-layout">
    <aside class="sehat-sidebar" id="sehatSidebar">
        <div class="sehat-sidebar-brand">
            <div class="logo-mark"><i class="bi bi-heart-pulse"></i></div>
            <div>
                <div class="brand-text">Sehat</div>
                <div class="brand-sub">Super Admin</div>
            </div>
        </div>
        <nav class="sehat-nav">
            <div class="sehat-nav-section">Main</div>
            <a href="{{ route('superadmin.dashboard') }}" class="sehat-nav-link {{ request()->routeIs('superadmin.dashboard') || request()->is('superadmin') || request()->is('superadmin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.customers.index') }}" class="sehat-nav-link {{ request()->routeIs('superadmin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Customers
            </a>
            <a href="{{ route('superadmin.plans.index') }}" class="sehat-nav-link {{ request()->routeIs('superadmin.plans.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i> Plans
            </a>
        </nav>
        <div class="p-3 border-top border-light border-opacity-10">
            <form action="{{ route('superadmin.logout') }}" method="POST">
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
                <i class="bi bi-shield-check text-sehat-orange"></i>
                <span>{{ auth('superadmin')->user()->name ?? 'Administrator' }}</span>
            </div>
        </header>

        <main class="sehat-content sehat-fade-in">
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
