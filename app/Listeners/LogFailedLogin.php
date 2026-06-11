<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        $user = $event->user;
        $email = $event->credentials['email'] ?? 'unknown';

        if ($user) {
            activity()
                ->useLog('authentication')
                ->event('failed')
                ->performedOn($user)
                ->causedBy($user)
                ->log("Failed login attempt for user: {$user->email} (wrong password).");
        } else {
            activity()
                ->useLog('authentication')
                ->event('failed')
                ->log("Failed login attempt for unknown email: {$email}.");
        }
    }
}
