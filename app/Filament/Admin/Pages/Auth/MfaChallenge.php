<?php

namespace App\Filament\Admin\Pages\Auth;

use App\Services\Google2FAService;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;

class MfaChallenge extends SimplePage
{
    protected string $view = 'filament.pages.auth.mfa-challenge';

    public ?string $google2faSecret = null;

    public ?string $qrCodeUrl = null;

    public bool $isSetupMode = false;

    public function getTitle(): string|Htmlable
    {
        return $this->isSetupMode ? 'Setup Keamanan Akun' : 'Verifikasi Dua Faktor';
    }

    public function getHeading(): string|Htmlable
    {
        return $this->isSetupMode ? 'Setup Keamanan Akun' : 'Verifikasi Dua Faktor';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->isSetupMode
            ? 'Pindai QR Code di bawah menggunakan Google Authenticator di HP Anda, lalu masukkan 6 digit kodenya.'
            : 'Masukkan 6 digit kode dari aplikasi Google Authenticator Anda untuk memverifikasi akun Anda.';
    }

    public function mount(): void
    {
        if (session('mfa_verified') === true) {
            redirect()->intended(filament()->getUrl());

            return;
        }

        $user = auth()->user();
        if (! $user) {
            redirect()->route('filament.admin.auth.login');

            return;
        }

        // If the user has no google2fa_secret set in DB, they are in Setup Mode
        if (empty($user->google2fa_secret)) {
            $this->isSetupMode = true;

            // Retrieve existing temporary secret from session or generate a new one
            $this->google2faSecret = session('temp_google2fa_secret');
            if (empty($this->google2faSecret)) {
                $this->google2faSecret = Google2FAService::generateSecretKey();
                session(['temp_google2fa_secret' => $this->google2faSecret]);
            }

            $this->qrCodeUrl = Google2FAService::getQrCodeUrl($user->email, $this->google2faSecret);
        }
    }
}
