<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        $user = $event->user;

        if ($user) {
            activity()
                ->useLog('authentication')
                ->event('logout')
                ->performedOn($user)
                ->causedBy($user)
                ->log("User {$user->name} ({$user->email}) logged out.");
        }
    }
}
