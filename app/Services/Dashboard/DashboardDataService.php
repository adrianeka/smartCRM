<?php

namespace App\Services\Dashboard;

use App\Models\User;

class DashboardDataService
{
    public function forUser(User $user): array
    {
        return [
            'tasks_today' => app(TaskOverviewService::class)->today($user),
            'deadlines' => app(TaskOverviewService::class)->upcomingDeadlines($user),
            'quick_links' => app(QuickLinksService::class)->forRole($user->role),
            'metrics' => app(MetricsSummaryService::class)->summary($user),
            'notifications' => app(NotificationFeedService::class)->latest($user),
        ];
    }
}
