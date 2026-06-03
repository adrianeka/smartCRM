<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MfaOtpMail;
use App\Models\MfaCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MfaOtpController extends Controller
{
    public function showChallenge(Request $request)
    {
        if ($request->session()->get('mfa_verified') === true) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return view('auth.mfa-challenge');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        $mfaCode = MfaCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$mfaCode) {
            return back()->withErrors(['code' => 'The provided code is invalid or has expired.']);
        }

        $mfaCode->update(['used' => true]);

        $request->session()->put('mfa_verified', true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function sendOtp(Request $request)
    {
        $this->generateAndSendOtp($request->user());

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    public function generateAndSendOtp(User $user)
    {
        // Invalidate previous unused codes
        MfaCode::where('user_id', $user->id)
            ->where('used', false)
            ->update(['used' => true]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        MfaCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new MfaOtpMail($code));
    }
}
