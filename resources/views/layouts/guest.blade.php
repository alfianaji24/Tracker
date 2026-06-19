<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tracker') }}</title>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Scripts and CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-wrapper">
        <!-- Left Side: Form -->
        <div class="auth-left">
            <div class="auth-card">
                <div style="text-align: center; margin-bottom: 32px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Sign In to {{ config('app.name', 'Tracker') }}</h2>
                    <p style="color: var(--text-muted);">Please enter your details to continue.</p>
                </div>
                
                {{ $slot }}
            </div>
        </div>

        <!-- Right Side: Branding -->
        <div class="auth-right">
            <i class='bx bx-water' style="font-size: 80px; margin-bottom: 24px;"></i>
            <h1 style="font-size: 40px; font-weight: 700; margin-bottom: 16px;">{{ config('app.name', 'Tracker') }}</h1>
            <p style="font-size: 18px; max-width: 400px; line-height: 1.6; opacity: 0.8;">
                Sistem Informasi Manajemen PDAM & Tagihan Air Terpadu.
            </p>
        </div>
    </div>
</body>
</html>
