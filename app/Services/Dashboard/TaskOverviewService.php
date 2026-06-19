<?php

namespace App\Services\Dashboard;

use App\Models\User;

class TaskOverviewService
{
    public function today(User $user): array
    {
        // This is a stub, wait for the actual task module schema to be stable
        // For now, return a placeholder or empty state
        return [];
    }

    public function upcomingDeadlines(User $user): array
    {
        // Stub for deadlines
        return [];
    }
}
