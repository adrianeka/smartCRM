<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Akun Anda Telah Diaktifkan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            width: 100% !important;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 24px;
        }
        .email-content {
            max-width: 570px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e8e8f1;
        }
        .email-header {
            background-color: #1e1e2d;
            padding: 24px;
            text-align: center;
            color: #ffffff;
        }
        .email-logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: -0.5px;
        }
        .email-logo-accent {
            color: #f59e0b; /* Amber */
        }
        .email-body {
            padding: 32px;
        }
        h1 {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 0;
            margin-bottom: 16px;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            color: #4b5563;
            margin-top: 0;
            margin-bottom: 24px;
        }
        .role-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
            padding: 6px 16px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .button {
            display: inline-block;
            background-color: #f59e0b;
            color: #ffffff !important;
            text-decoration: none !important;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #d97706;
        }
        .email-footer {
            text-align: center;
            padding: 24px;
            font-size: 12px;
            color: #9ca3af;
            background-color: #f9fafb;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                <div class="email-logo">Smart<span class="email-logo-accent">CRM</span></div>
            </div>
            <div class="email-body">
                <h1>Halo, {{ $user->name }}!</h1>
                <p>Kabar baik! Pendaftaran akun Anda telah disetujui oleh Administrator, dan akun Anda telah aktif.</p>
                
                <p>Anda telah diberikan hak akses dengan peran (role) berikut:</p>
                <div class="role-badge">{{ $roleName }}</div>
                
                <p>Sekarang Anda dapat masuk dan mengakses seluruh fitur di dashboard SmartCRM.</p>
                
                <div style="text-align: center; margin-top: 32px;">
                    <a href="{{ route('filament.admin.auth.login') }}" class="button">Masuk ke Dashboard</a>
                </div>
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} SmartCRM. Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</body>
</html>
