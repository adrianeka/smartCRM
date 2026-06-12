<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MarketingStatsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Active Campaigns', '12')
                ->description('3 launching this week')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('primary'),
            Stat::make('Leads Generated', '840')
                ->description('15% increase from last month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 12, 10, 15, 20, 25, 30]),
            Stat::make('Avg. Open Rate', '24.8%')
                ->description('Industry average is 21%')
                ->descriptionIcon('heroicon-m-envelope-open')
                ->color('info'),
            Stat::make('Cost per Lead', '$12.50')
                ->description('Decreased by $2.10')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success'),
        ];
    }
}
