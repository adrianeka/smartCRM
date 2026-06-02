<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider(string $provider)
    {
        if ($provider !== 'google') {
            abort(404);
        }
        
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider, Request $request)
    {
        if ($provider !== 'google') {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Failed to authenticate with Google.']);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update provider if empty
            if (empty($user->provider_id)) {
                $user->update([
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            }
        } else {
            // Register new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => null,
                'provider_name' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('Guest');
        }

        Auth::login($user);

        // Intercept for MFA OTP
        app(MfaOtpController::class)->generateAndSendOtp($user);
        $request->session()->put('mfa_verified', false);

        return redirect()->route('mfa.challenge');
    }
}
