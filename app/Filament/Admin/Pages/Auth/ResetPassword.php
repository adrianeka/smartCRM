<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\PasswordReset\ResetPassword as BaseResetPassword;
use Filament\Schemas\Components\Component;

class ResetPassword extends BaseResetPassword
{
    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->helperText(view('filament.components.password-criteria'));
    }
}
