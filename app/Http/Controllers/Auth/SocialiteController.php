<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use OpenApi\Attributes as OA;

class SocialiteController extends Controller
{
    #[OA\Get(
        path: '/auth/google/redirect',
        summary: 'Redirect to Google OAuth',
        tags: ['OAuth']
    )]
    public function redirectToProvider(string $provider)
    {
        if ($provider !== 'google') {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    #[OA\Get(
        path: '/auth/google/callback',
        summary: 'Google OAuth Callback',
        tags: ['OAuth']
    )]
    public function handleProviderCallback(string $provider, Request $request)
    {
        if ($provider !== 'google') {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/admin/login')->withErrors(['email' => 'Failed to authenticate with Google.']);
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

        }

        Auth::login($user);

        // Intercept for MFA OTP
        app(MfaOtpController::class)->generateAndSendOtp($user);
        $request->session()->put('mfa_verified', false);

        return redirect()->route('filament.admin.pages.auth.mfa-challenge');
    }
}
