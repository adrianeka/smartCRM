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
        <title>Menunggu Persetujuan Admin - {{ config('app.name', 'SmartCRM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <style>
            :root {
                --bg-color: #FAFAFA;
                --text-color: #1f2937;
                --card-bg: #ffffff;
                --card-border: #e5e7eb;
                --text-muted: #4b5563;
                --amber-primary: #f59e0b;
                --amber-hover: #d97706;
                --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
                --badge-bg: #fef3c7;
                --badge-text: #b45309;
                --badge-border: #fde68a;
            }

            .dark {
                --bg-color: #121212;
                --text-color: #f5f5f5;
                --card-bg: #1e1e1e;
                --card-border: #2e2e2e;
                --text-muted: #a3a3a3;
                --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
                --badge-bg: rgba(245, 158, 11, 0.1);
                --badge-text: #fbbf24;
                --badge-border: rgba(245, 158, 11, 0.2);
            }

            body {
                background-color: var(--bg-color);
                color: var(--text-color);
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                box-sizing: border-box;
                position: relative;
                overflow: hidden;
                transition: background-color 0.3s;
            }
            
            /* Background Glow Effect */
            .bg-glow {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(245, 158, 11, 0.06) 0%, rgba(255,255,255,0) 70%);
                transform: translate(-50%, -50%);
                z-index: -1;
                pointer-events: none;
            }
            
            .dark .bg-glow {
                background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, rgba(22, 27, 33, 0) 70%);
            }

            .card {
                width: 100%;
                max-width: 440px;
                background-color: var(--card-bg);
                border: 1px solid var(--card-border);
                border-radius: 20px;
                padding: 40px;
                box-shadow: var(--shadow);
                text-align: center;
                box-sizing: border-box;
                z-index: 10;
                position: relative;
                transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s;
            }

            .logo {
                font-size: 24px;
                font-weight: 800;
                letter-spacing: -0.5px;
                margin-bottom: 24px;
                color: var(--text-color);
            }

            .logo-accent {
                color: var(--amber-primary);
            }

            .icon-container {
                width: 80px;
                height: 80px;
                background-color: rgba(245, 158, 11, 0.1);
                color: var(--amber-primary);
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 24px;
                animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {
                0%, 100% {
                    transform: scale(1);
                    opacity: 1;
                }
                50% {
                    transform: scale(1.04);
                    opacity: 0.85;
                }
            }

            .title {
                font-size: 22px;
                font-weight: 700;
                margin-top: 0;
                margin-bottom: 8px;
                color: var(--text-color);
            }

            .badge {
                display: inline-flex;
                align-items: center;
                padding: 6px 16px;
                background-color: var(--badge-bg);
                color: var(--badge-text);
                border: 1px solid var(--badge-border);
                border-radius: 9999px;
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 24px;
                box-sizing: border-box;
            }

            .description {
                font-size: 14px;
                line-height: 1.6;
                color: var(--text-muted);
                margin-top: 0;
                margin-bottom: 32px;
            }

            .description strong {
                color: var(--text-color);
                font-weight: 600;
            }

            .btn-container {
                display: flex;
                flex-direction: column;
                gap: 12px;
                width: 100%;
            }

            .btn-primary {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 12px 20px;
                background-color: var(--amber-primary);
                color: #ffffff;
                border: none;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                box-shadow: 0 4px 6px rgba(245, 158, 11, 0.15);
                cursor: pointer;
                transition: background-color 0.2s, transform 0.1s;
                box-sizing: border-box;
            }

            .btn-primary:hover {
                background-color: var(--amber-hover);
            }

            .btn-primary:active {
                transform: scale(0.98);
            }

            .btn-secondary {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 12px 20px;
                background-color: transparent;
                color: var(--text-muted);
                border: none;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
                transition: background-color 0.2s, color 0.2s;
                box-sizing: border-box;
            }

            .btn-secondary:hover {
                background-color: var(--card-border);
                color: #ef4444;
            }

            .icon-svg {
                width: 16px;
                height: 16px;
                margin-right: 8px;
                fill: none;
                stroke: currentColor;
                stroke-width: 2;
            }
        </style>
    </head>
    <body>
        
        <!-- Background Glow -->
        <div class="bg-glow"></div>

        <div class="card">
            <!-- App Logo / Name -->
            <div class="logo">
                Smart<span class="logo-accent">CRM</span>
            </div>

            <!-- Icon Container -->
            <div class="icon-container">
                <!-- Clock / Hourglass Icon -->
                <svg class="icon-svg" style="width: 40px; height: 40px; stroke-width: 1.8;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                </svg>
            </div>

            <!-- Header and Status -->
            <h1 class="title">
                Pendaftaran Berhasil!
            </h1>
            <div class="badge">
                Menunggu Persetujuan Admin
            </div>

            <!-- Informative Text -->
            <p class="description">
                Akun Anda dengan email <strong>{{ Auth::user()->email }}</strong> telah berhasil terdaftar.
                <br />
                Namun saat ini Anda belum memiliki hak akses (Role). Silakan hubungi Administrator Anda untuk memberikan Role agar Anda dapat masuk ke dalam sistem.
            </p>

            <!-- Buttons -->
            <div class="btn-container">
                <!-- Refresh Button -->
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn-primary">
                    <svg class="icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                    </svg>
                    Perbarui Status Halaman
                </a>

                <!-- Logout Link -->
                <a href="{{ route('filament.admin.auth.logout.get') }}" class="btn-secondary">
                    <svg class="icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"></path>
                    </svg>
                    Keluar / Logout
                </a>
            </div>
        </div>
    </body>
</html>
