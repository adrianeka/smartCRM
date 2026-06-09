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
        return 'Masukkan 6 digit kode yang telah kami kirimkan ke email Anda untuk memverifikasi akun Anda.';
    }

    public function mount(): void
    {
        if (session('mfa_verified') === true) {
            redirect()->intended(filament()->getUrl());
        }
    }
}
