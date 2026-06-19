<?php

namespace App\Filament\Admin\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;

/**
 * @property \Filament\Forms\Form $form
 * @method void rateLimit(int $maxAttempts)
 * @method \Filament\Notifications\Notification|null getRateLimitedNotification(\Exception $exception)
 * @method array getCredentialsFromFormData(array $data)
 * @method void throwFailureValidationException()
 */
class Login extends BaseLogin
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            if (session('mfa_verified') !== true) {
                Filament::auth()->logout();
                session()->invalidate();
                session()->regenerateToken();
            } else {
                redirect()->intended(Filament::getUrl());
            }
        }

        $this->form->fill();
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();
        session()->put('mfa_verified', false);

        return app(LoginResponse::class);
    }
}

