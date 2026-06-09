<?php

use App\Filament\Admin\Pages\Auth\MfaChallenge;
use App\Http\Controllers\Auth\MfaOtpController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Handle GET requests to admin/logout (e.g. direct browser navigation)
Route::get('admin/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('filament.admin.auth.logout.get');

// MFA OTP Routes
Route::middleware('auth')->group(function () {
    Route::get('admin/mfa-challenge', MfaChallenge::class)
        ->name('filament.admin.pages.auth.mfa-challenge');

    Route::get('admin/mfa-challenge-alias', MfaChallenge::class)
        ->name('mfa.challenge');

    Route::post('mfa/challenge/send', [MfaOtpController::class, 'sendOtp'])
        ->name('mfa.challenge.send');
    Route::post('mfa/challenge/verify', [MfaOtpController::class, 'verifyOtp'])
        ->name('mfa.challenge.verify');
});

// Session Management Routes (Requires fully verified MFA)
Route::middleware(['auth', 'mfa.verified'])->group(function () {
    Route::post('session/logout-others', [SessionController::class, 'logoutOtherDevices'])
        ->name('session.logout-others');
});

// Google OAuth Routes
Route::get('auth/{provider}/redirect', [SocialiteController::class, 'redirectToProvider'])
    ->name('socialite.redirect');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])
    ->name('socialite.callback');
