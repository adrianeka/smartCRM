<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
    <script>
        // Sync dark mode with Filament's theme preference
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'SmartCRM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            /* Soft radial glow effect to mimic the image background */
            .bg-glow {
                position: absolute;
                top: 50%;
                left: 30%;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(245, 158, 11, 0.05) 0%, rgba(255,255,255,0) 70%); /* Amber glow */
                transform: translate(-50%, -50%);
                z-index: -1;
                pointer-events: none;
            }
            
            .dark .bg-glow {
                background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, rgba(22, 27, 33, 0) 70%); /* Dark mode amber glow */
            }
        </style>
    </head>
    <body class="bg-[#FAFAFA] dark:bg-neutral-900 text-neutral-900 dark:text-neutral-100 min-h-screen selection:bg-primary-500 selection:text-white relative overflow-hidden flex flex-col">
        
        <!-- Background Glow -->
        <div class="bg-glow"></div>

        <!-- Navbar -->
        <header class="w-full px-6 py-5 sm:px-12 flex items-center justify-between z-50 relative border-b border-neutral-200 dark:border-neutral-800 bg-white/60 dark:bg-neutral-900/60 backdrop-blur-sm">
            <div class="text-xl font-extrabold tracking-tight text-neutral-900 dark:text-white">
                SmartCRM
            </div>
            
            <nav class="flex items-center gap-4 sm:gap-6">
                <!-- Theme Switcher -->
                <div class="relative inline-block text-left" id="theme-menu-container">
                    <button type="button" id="theme-menu-button" class="flex items-center justify-center w-8 h-8 rounded-full text-neutral-500 hover:text-amber-600 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors" aria-expanded="true" aria-haspopup="true">
                        <!-- Sun Icon (Default) -->
                        <svg id="icon-sun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <!-- Moon Icon -->
                        <svg id="icon-moon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <!-- System Icon -->
                        <svg id="icon-system" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </button>

                    <div id="theme-dropdown" class="absolute right-0 z-20 mt-2 w-36 origin-top-right rounded-xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden transition-all" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="py-1 p-1" role="none">
                            <button onclick="setTheme('light')" class="text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:text-amber-600 dark:hover:text-amber-500 rounded-lg group flex w-full items-center px-3 py-2 text-sm transition-colors" role="menuitem">
                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Light
                            </button>
                            <button onclick="setTheme('dark')" class="text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:text-amber-600 dark:hover:text-amber-500 rounded-lg group flex w-full items-center px-3 py-2 text-sm transition-colors" role="menuitem">
                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                Dark
                            </button>
                            <button onclick="setTheme('system')" class="text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:text-amber-600 dark:hover:text-amber-500 rounded-lg group flex w-full items-center px-3 py-2 text-sm transition-colors" role="menuitem">
                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                System
                            </button>
                        </div>
                    </div>
                </div>

                <a href="{{ url('/admin/login') }}" class="text-sm font-semibold text-neutral-600 dark:text-neutral-300 hover:text-amber-600 dark:hover:text-amber-500 transition-colors px-3 py-2">
                    Masuk
                </a>
                
                <a href="{{ url('/admin/register') }}" class="text-sm font-semibold text-neutral-600 dark:text-neutral-300 hover:text-amber-600 dark:hover:text-amber-500 transition-colors px-3 py-2">
                    Daftar
                </a>
            </nav>
        </header>

        <!-- Main Hero Content -->
        <main class="flex-1 w-full px-6 sm:px-12 lg:px-24 flex items-center z-10 relative">
            <div class="max-w-4xl pt-10 pb-32">
                <h1 class="text-5xl sm:text-6xl md:text-7xl font-semibold tracking-tight text-neutral-900 dark:text-white mb-2 leading-tight">
                    Selamat Datang di
                </h1>
                <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold tracking-tight text-amber-600 dark:text-amber-500 leading-tight">
                    Smart CRM
                </h1>
            </div>
        </main>

        <script>
            // Theme toggler logic
            const themeButton = document.getElementById('theme-menu-button');
            const themeDropdown = document.getElementById('theme-dropdown');
            
            themeButton.addEventListener('click', (e) => {
                e.stopPropagation();
                themeDropdown.classList.toggle('hidden');
            });
            
            document.addEventListener('click', (e) => {
                if (!themeDropdown.contains(e.target) && !themeButton.contains(e.target)) {
                    themeDropdown.classList.add('hidden');
                }
            });

            function updateThemeIcon() {
                const theme = localStorage.getItem('theme');
                document.getElementById('icon-sun').classList.add('hidden');
                document.getElementById('icon-moon').classList.add('hidden');
                document.getElementById('icon-system').classList.add('hidden');

                if (theme === 'light') {
                    document.getElementById('icon-sun').classList.remove('hidden');
                } else if (theme === 'dark') {
                    document.getElementById('icon-moon').classList.remove('hidden');
                } else {
                    document.getElementById('icon-system').classList.remove('hidden');
                }
            }

            function setTheme(mode) {
                if (mode === 'system') {
                    localStorage.removeItem('theme');
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } else {
                    localStorage.setItem('theme', mode);
                    if (mode === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
                updateThemeIcon();
                themeDropdown.classList.add('hidden');
            }

            // Initialize icon on load
            updateThemeIcon();

            // Listen for OS theme changes if on system mode
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
        </script>
    </body>
</html>
