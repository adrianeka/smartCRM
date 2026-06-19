<?php

namespace App\Services\Dashboard;

use App\Models\User;

class MetricsSummaryService
{
    public function summary(User $user): array
    {
        return [
            'sales_this_month' => '0',
            'open_opportunities' => '0',
            'conversion_rate' => '0',
            'open_tickets' => '0',
        ];
    }

    public function salesSummary(User $user): array
    {
        return [
            'sales_this_month' => '0',
            'open_opportunities' => '0',
            'conversion_rate' => '0',
        ];
    }

    public function ticketsSummary(User $user): array
    {
        return [
            'open_tickets' => '0',
            'escalated_tickets' => '0',
        ];
    }
}
