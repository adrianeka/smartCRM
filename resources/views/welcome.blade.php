<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartCRM - Intelligent Customer Management</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .mesh-bg {
            background-color: #000000;
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                              radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                              radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        }
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(90deg, #60A5FA, #A78BFA, #F472B6);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="antialiased bg-gray-900 text-gray-100 min-h-screen flex flex-col mesh-bg relative overflow-x-hidden">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-panel border-b-0 border-white/10 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-white">Smart<span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">CRM</span></span>
                </div>
                
                @if (Route::has('login'))
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors duration-200">
                                Enter Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors duration-200">
                                Sign in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 bg-white/10 border border-white/20 rounded-full hover:bg-white/20 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow flex items-center justify-center pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full text-center">
            <!-- Decorative Elements -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none"></div>
            
            <div class="glass-panel rounded-3xl p-8 md:p-16 max-w-4xl mx-auto shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-700"></div>
                
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-white mb-6">
                    Manage Customer Relationships <br />
                    <span class="text-gradient">Intelligently.</span>
                </h1>
                
                <p class="mt-4 text-lg md:text-xl text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Elevate your business with an intuitive, powerful, and seamless CRM platform designed for modern teams. Say goodbye to scattered data.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white transition-all duration-300 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-500 hover:to-purple-500 hover:shadow-[0_0_30px_rgba(168,85,247,0.5)] hover:-translate-y-1">
                            Go to Dashboard
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white transition-all duration-300 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-500 hover:to-purple-500 hover:shadow-[0_0_30px_rgba(168,85,247,0.5)] hover:-translate-y-1">
                            Start Free Trial
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-gray-300 transition-all duration-300 bg-white/5 border border-white/10 rounded-full hover:bg-white/10 hover:text-white">
                            Sign in to Account
                        </a>
                    @endauth
                </div>
                
                <div class="mt-12 flex items-center justify-center gap-6 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        No credit card required
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        14-day free trial
                    </div>
                </div>
            </div>
            
            <!-- Dashboard Preview Graphic (Abstract) -->
            <div class="mt-20 relative max-w-5xl mx-auto animate-float">
                <div class="glass-panel rounded-2xl p-4 shadow-2xl border-t border-white/20">
                    <div class="flex gap-2 mb-4">
                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-1 space-y-4">
                            <div class="h-8 bg-white/5 rounded-lg w-full"></div>
                            <div class="h-24 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-xl w-full border border-white/5"></div>
                            <div class="h-8 bg-white/5 rounded-lg w-3/4"></div>
                        </div>
                        <div class="col-span-2 space-y-4">
                            <div class="h-32 bg-white/5 rounded-xl w-full relative overflow-hidden">
                                <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-blue-500/20 to-transparent"></div>
                                <svg class="absolute bottom-0 w-full text-blue-500/30" preserveAspectRatio="none" viewBox="0 0 100 100" height="100%"><path d="M0,100 L0,50 Q25,20 50,60 T100,30 L100,100 Z" fill="currentColor"></path></svg>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="h-20 bg-white/5 rounded-xl w-full"></div>
                                <div class="h-20 bg-white/5 rounded-xl w-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="border-t border-white/10 mt-auto py-8 text-center text-sm text-gray-500">
        <p>&copy; {{ date('Y') }} SmartCRM Inc. All rights reserved. Built with Laravel.</p>
    </footer>
</body>
</html>
