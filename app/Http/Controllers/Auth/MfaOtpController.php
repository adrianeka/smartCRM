<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Google2FAService;
use Illuminate\Http\Request;

class MfaOtpController extends Controller
{
    public function showChallenge(Request $request)
    {
        if ($request->session()->get('mfa_verified') === true) {
            return redirect()->intended(route('filament.admin.pages.dashboard'));
        }

        return view('auth.mfa-challenge');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('filament.admin.auth.login');
        }

        // Setup Mode (User has no secret in DB yet)
        if (empty($user->google2fa_secret)) {
            $tempSecret = session('temp_google2fa_secret');
            if (empty($tempSecret)) {
                return back()->withErrors(['code' => 'Sesi setup kadaluwarsa. Silakan muat ulang halaman ini.']);
            }

            if (! Google2FAService::verify($tempSecret, $request->code)) {
                return back()->withErrors(['code' => 'Kode yang Anda masukkan tidak valid. Silakan coba lagi.']);
            }

            // Save secret key permanently
            $user->update([
                'google2fa_secret' => $tempSecret,
            ]);

            session()->forget('temp_google2fa_secret');
        } else {
            // Verification Mode
            if (! Google2FAService::verify($user->google2fa_secret, $request->code)) {
                return back()->withErrors(['code' => 'Kode yang Anda masukkan tidak valid. Silakan coba lagi.']);
            }
        }

        $request->session()->put('mfa_verified', true);
        $request->session()->regenerate();

        return redirect()->intended(route('filament.admin.pages.dashboard'));
    }
}
