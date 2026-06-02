<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Auth\Events\Registered;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Http\RedirectResponse;

use Filament\Schemas\Components\Component;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{
    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->helperText(view('filament.components.password-criteria'));
    }
    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
        
        // Assign Guest role to newly registered users
        $user->assignRole('Guest');
        
        return $user;
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        if ($this->isRegisterRateLimited($this->data['email'] ?? '')) {
            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function (): Model {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        // Send OTP and set MFA session
        app(\App\Http\Controllers\Auth\MfaOtpController::class)->generateAndSendOtp($user);
        session()->put('mfa_verified', false);

        // Redirect to MFA Challenge
        return new class implements RegistrationResponse {
            public function toResponse($request)
            {
                return redirect()->route('filament.admin.pages.auth.mfa-challenge');
            }
        };
    }
}
