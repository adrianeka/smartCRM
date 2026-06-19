<?php

namespace App\Services\Dashboard;

use App\Models\User;

class NotificationFeedService
{
    public function latest(User $user): array
    {
        return $user->unreadNotifications()->take(5)->get()->toArray();
    }
}
