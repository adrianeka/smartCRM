<?php

namespace App\Services\Dashboard;

use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class TaskOverviewService
{
    public function today(User $user): array
    {
        $tasks = Customer::whereDate('next_follow_up_at', Carbon::today())
            ->orderBy('next_follow_up_at', 'asc')
            ->limit(10)
            ->get();

        return $tasks->map(fn (Customer $c) => [
            'id' => $c->id,
            'title' => $c->full_name,
            'company' => $c->company_name ?? 'Personal',
            'time' => $c->next_follow_up_at?->format('H:i') ?? 'N/A',
            'status' => $c->status,
            'notes' => $c->notes ?? 'Follow-up required',
        ])->toArray();
    }

    public function upcomingDeadlines(User $user): array
    {
        $deadlines = Customer::whereNotNull('next_follow_up_at')
            ->where('next_follow_up_at', '>', now())
            ->orderBy('next_follow_up_at', 'asc')
            ->limit(10)
            ->get();

        return $deadlines->map(fn (Customer $c) => [
            'id' => $c->id,
            'title' => $c->full_name,
            'company' => $c->company_name ?? 'Personal',
            'date' => $c->next_follow_up_at?->format('Y-m-d') ?? 'N/A',
            'time' => $c->next_follow_up_at?->format('H:i') ?? 'N/A',
            'notes' => $c->notes ?? 'No notes available',
        ])->toArray();
    }
}
