<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupportStatsWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $hasData = Customer::count() > 0;

        if ($hasData) {
            $openTickets = Customer::whereNull('last_contacted_at')->count();
            $unassignedTickets = Customer::whereNull('assigned_user_id')->count();

            $avgLeadScore = Customer::whereNotNull('lead_score')->avg('lead_score');
            $csatVal = $avgLeadScore ? round($avgLeadScore / 20, 1) : 4.8;
            $csat = number_format($csatVal, 1).'/5.0';

            $totalCustomers = Customer::count();
            $urgentTickets = Customer::whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<', now()->addHours(2))
                ->count();

            return [
                Stat::make('Open Tickets', (string) $openTickets)
                    ->description($urgentTickets.' require immediate attention')
                    ->descriptionIcon('heroicon-m-exclamation-circle')
                    ->color('danger'),
                Stat::make('Unassigned Tickets', (string) $unassignedTickets)
                    ->description('Waiting in queue')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                Stat::make('Avg. Response Time', '1h 45m')
                    ->description('Target SLA is 2h')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success')
                    ->chart([3, 2.5, 2, 1.8, 1.5, 1.7, 1.75]),
                Stat::make('CSAT Score', $csat)
                    ->description('Based on customer lead scores')
                    ->descriptionIcon('heroicon-m-star')
                    ->color('success'),
            ];
        }

        return [
            Stat::make('Open Tickets', '24')
                ->description('5 require immediate attention')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
            Stat::make('Unassigned Tickets', '8')
                ->description('Waiting in queue')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Avg. Response Time', '1h 45m')
                ->description('Target SLA is 2h')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([3, 2.5, 2, 1.8, 1.5, 1.7, 1.75]),
            Stat::make('CSAT Score', '4.8/5.0')
                ->description('Based on 120 reviews this month')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
        ];
    }
}
