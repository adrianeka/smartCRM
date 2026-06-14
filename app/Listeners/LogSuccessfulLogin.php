<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        activity()
            ->useLog('authentication')
            ->event('login')
            ->performedOn($user)
            ->causedBy($user)
            ->log("User {$user->name} logged in successfully.");
    }
}
