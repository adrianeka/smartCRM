<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartCRM - Kelola Hubungan Pelanggan dengan Cerdas</title>
    <meta name="description" content="SmartCRM adalah platform CRM modern untuk mengelola hubungan pelanggan dengan cerdas dan efisien.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #EBF5FE 0%, #F0F7FF 50%, #F5FAFF 100%);
        }
        .float-animation {
            animation: floatUp 6s ease-in-out infinite;
        }
        @keyframes floatUp {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .blob-1 {
            animation: blobMove1 8s ease-in-out infinite;
        }
        .blob-2 {
            animation: blobMove2 10s ease-in-out infinite;
        }
        @keyframes blobMove1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.05); }
            66% { transform: translate(-15px, 15px) scale(0.95); }
        }
        @keyframes blobMove2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-25px, 20px) scale(0.95); }
            66% { transform: translate(20px, -10px) scale(1.05); }
        }
    </style>
</head>
<body class="antialiased bg-white text-neutral-800 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-neutral-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <a href="/" class="text-xl font-bold text-neutral-900 tracking-tight">
                Smart<span class="text-primary-500">CRM</span>
            </a>
            @if (Route::has('login'))
                <div class="flex items-center space-x-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-neutral-600 hover:text-primary-500 transition-colors duration-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-neutral-600 hover:text-primary-500 transition-colors duration-200">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-medium text-neutral-600 hover:text-primary-500 transition-colors duration-200">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[calc(100vh-4rem)] py-12">
                <!-- Left: Text Content -->
                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-neutral-900 leading-tight tracking-tight">
                            Selamat Datang di
                            <span class="text-primary-500">Smart CRM</span>
                        </h1>
                        <p class="mt-6 text-lg text-neutral-500 leading-relaxed max-w-lg">
                            Platform CRM modern yang membantu Anda mengelola hubungan pelanggan dengan cerdas, efisien, dan terorganisir.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary inline-flex items-center justify-center w-auto px-8 py-3.5">
                                Masuk ke Dashboard
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary inline-flex items-center justify-center w-auto px-8 py-3.5">
                                Mulai Sekarang
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                            <a href="{{ route('login') }}" class="btn-secondary inline-flex items-center justify-center w-auto px-8 py-3.5">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right: Illustration / Decorative Element -->
                <div class="relative hidden lg:flex items-center justify-center">
                    <!-- Background blobs -->
                    <div class="absolute w-72 h-72 bg-primary-100 rounded-full blur-3xl opacity-60 blob-1 -top-8 -right-8"></div>
                    <div class="absolute w-56 h-56 bg-info-100 rounded-full blur-3xl opacity-50 blob-2 bottom-8 left-8"></div>

                    <!-- Dashboard Preview Card -->
                    <div class="relative float-animation">
                        <div class="bg-white rounded-2xl shadow-elevated p-6 w-96 border border-neutral-100">
                            <!-- Fake browser dots -->
                            <div class="flex gap-2 mb-5">
                                <div class="w-3 h-3 rounded-full bg-error-400"></div>
                                <div class="w-3 h-3 rounded-full bg-warning-400"></div>
                                <div class="w-3 h-3 rounded-full bg-success-400"></div>
                            </div>

                            <!-- Fake Dashboard Content -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="h-3 bg-neutral-100 rounded-full w-24"></div>
                                        <div class="h-2 bg-neutral-50 rounded-full w-16 mt-1.5"></div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-bold text-primary-500">+12%</span>
                                    </div>
                                </div>

                                <div class="h-24 bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-4 flex items-end justify-between">
                                    <div class="flex gap-1 items-end">
                                        <div class="w-4 h-6 bg-primary-200 rounded-sm"></div>
                                        <div class="w-4 h-10 bg-primary-300 rounded-sm"></div>
                                        <div class="w-4 h-8 bg-primary-200 rounded-sm"></div>
                                        <div class="w-4 h-14 bg-primary-500 rounded-sm"></div>
                                        <div class="w-4 h-10 bg-primary-300 rounded-sm"></div>
                                        <div class="w-4 h-12 bg-primary-400 rounded-sm"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-semibold text-primary-600">2,847</div>
                                        <div class="text-[10px] text-primary-400">Pelanggan</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-success-50 rounded-xl p-3 border border-success-100">
                                        <div class="text-xs text-success-600 font-medium">Aktif</div>
                                        <div class="text-lg font-bold text-success-700 mt-0.5">1,204</div>
                                    </div>
                                    <div class="bg-warning-50 rounded-xl p-3 border border-warning-100">
                                        <div class="text-xs text-warning-600 font-medium">Follow-up</div>
                                        <div class="text-lg font-bold text-warning-700 mt-0.5">328</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-neutral-100 py-6 text-center text-sm text-neutral-400">
        <p>&copy; {{ date('Y') }} SmartCRM. All rights reserved.</p>
    </footer>
</body>
</html>
