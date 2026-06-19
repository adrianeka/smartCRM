<?php

namespace App\Filament\Admin\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Events\Registered;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \Filament\Schemas\Schema $form
 * @property array $data
 * @method void rateLimit(int $maxAttempts)
 * @method \Filament\Notifications\Notification|null getRateLimitedNotification(\Exception $exception)
 * @method bool isRegisterRateLimited(string $email)
 * @method \Illuminate\Database\Eloquent\Model wrapInDatabaseTransaction(\Closure $callback)
 * @method void callHook(string $hook)
 * @method array mutateFormDataBeforeRegister(array $data)
 * @method \Illuminate\Database\Eloquent\Model handleRegistration(array $data)
 * @method void sendEmailVerificationNotification(\Illuminate\Database\Eloquent\Model $user)
 */
class Register extends BaseRegister
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            // If user hits Back from MFA challenge (cancelling setup), log them out
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
    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->helperText(view('filament.components.password-criteria'));
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

        // Set MFA session to false (forces setup on first login)
        session()->put('mfa_verified', false);

        // Redirect to MFA Challenge
        return new class implements RegistrationResponse
        {
            public function toResponse($request)
            {
                return redirect()->route('filament.admin.pages.auth.mfa-challenge');
            }
        };
    }
}
