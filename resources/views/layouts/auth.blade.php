<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign in') � Sehat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/sehat-theme.css') }}?v=3" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="sehat-auth-shell">
    <div class="sehat-auth-card">
        @yield('auth_brand')
        @if(session('success'))
            <div class="alert sehat-alert sehat-alert-success py-2 mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert sehat-alert sehat-alert-danger py-2 mb-3">{{ session('error') }}</div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="alert sehat-alert sehat-alert-danger py-2 mb-3">
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/sehat-app.js') }}"></script>
@stack('scripts')
</body>
</html>

