<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SmartCRM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-neutral-50 text-neutral-800">
        <!-- Navbar -->
        <nav class="auth-navbar">
            <div class="auth-navbar-inner">
                <a href="/" class="auth-navbar-brand">
                    Smart<span class="text-primary-500">CRM</span>
                </a>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'auth-navbar-link-active' : 'auth-navbar-link' }}">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'auth-navbar-link-active' : 'auth-navbar-link' }}">
                        Daftar
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="min-h-screen flex items-center justify-center pt-20 pb-12 px-4">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
