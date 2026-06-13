<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        activity()
            ->useLog('authentication')
            ->event('login')
            ->performedOn($user)
            ->causedBy($user)
            ->log("User {$user->name} logged in successfully.");
    }
}
