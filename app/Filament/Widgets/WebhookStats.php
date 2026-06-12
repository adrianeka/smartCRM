<?php

namespace App\Filament\Widgets;

use App\Models\WebhookLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WebhookStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Webhooks',
                (string) WebhookLog::query()->count('*')
            ),

            Stat::make(
                'Success',
                (string) WebhookLog::query()
                    ->where('status', 'success')
                    ->count('*')
            ),

            Stat::make(
                'Failed',
                (string) WebhookLog::query()
                    ->where('status', 'failed')
                    ->count('*')
            ),

            Stat::make(
                'Pending',
                (string) WebhookLog::query()
                    ->where('status', 'pending')
                    ->count('*')
            ),
        ];
    }
}
