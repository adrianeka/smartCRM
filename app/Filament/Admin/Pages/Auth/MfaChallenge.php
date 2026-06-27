<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;

class MfaChallenge extends SimplePage
{
    protected string $view = 'filament.pages.auth.mfa-challenge';

    public function getTitle(): string|Htmlable
    {
        return 'Verifikasi Dua Faktor';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Verifikasi Dua Faktor';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Enter the 6-digit code we sent to your email to verify your account.';
    }

    public function mount(): void
    {
        if (session('mfa_verified') === true) {
            redirect()->intended(filament()->getUrl());
        }
    }
}
