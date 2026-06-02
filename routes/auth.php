<?php

use Illuminate\Support\Facades\Route;

// MFA OTP Routes
Route::middleware('auth')->group(function () {
    Route::get('admin/mfa-challenge', \App\Filament\Admin\Pages\Auth\MfaChallenge::class)
        ->name('filament.admin.pages.auth.mfa-challenge');

    Route::get('admin/mfa-challenge-alias', \App\Filament\Admin\Pages\Auth\MfaChallenge::class)
        ->name('mfa.challenge');

    Route::post('mfa/challenge/send', [\App\Http\Controllers\Auth\MfaOtpController::class, 'sendOtp'])
        ->name('mfa.challenge.send');
    Route::post('mfa/challenge/verify', [\App\Http\Controllers\Auth\MfaOtpController::class, 'verifyOtp'])
        ->name('mfa.challenge.verify');
});

// Session Management Routes (Requires fully verified MFA)
Route::middleware(['auth', 'mfa.verified'])->group(function () {
    Route::post('session/logout-others', [\App\Http\Controllers\Auth\SessionController::class, 'logoutOtherDevices'])
        ->name('session.logout-others');
});

// Google OAuth Routes
Route::get('auth/{provider}/redirect', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToProvider'])
    ->name('socialite.redirect');
Route::get('auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleProviderCallback'])
    ->name('socialite.callback');
