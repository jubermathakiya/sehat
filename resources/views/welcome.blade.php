<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sehat � Doctor Management SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/sehat-theme.css') }}?v=3" rel="stylesheet">
</head>
<body>
<div class="sehat-landing">
    <div class="sehat-landing-hero">
        <div class="mb-4">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 p-3" style="background: rgba(249,115,22,0.28);">
                <i class="bi bi-heart-pulse fs-2"></i>
            </span>
        </div>
        <h1>Sehat</h1>
        <p>Modern multi-clinic doctor management � appointments, patients, prescriptions, and reporting in one secure platform.</p>
        <div class="sehat-landing-cta">
            <a href="{{ route('superadmin.login') }}" class="btn btn-lg btn-sehat-primary px-4">
                <i class="bi bi-shield-lock me-2"></i> Super Admin Login
            </a>
            <a href="{{ route('tenant.login') }}" class="btn btn-lg btn-sehat-accent px-4">
                <i class="bi bi-hospital me-2"></i> Clinic Login
            </a>
        </div>
    </div>
    <p class="small mt-5 opacity-50 position-relative" style="z-index: 1;">&copy; {{ date('Y') }} Sehat. Medical practice management.</p>
</div>
</body>
</html>
