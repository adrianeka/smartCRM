<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        /** @var User|null $user */
        $user = $event->user;

        if ($user) {
            activity()
                ->useLog('authentication')
                ->event('logout')
                ->performedOn($user)
                ->causedBy($user)
                ->log("User {$user->name} logged out.");
        }
    }
}
