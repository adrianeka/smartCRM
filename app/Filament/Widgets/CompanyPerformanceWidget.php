<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CompanyPerformanceWidget extends BaseWidget
{
    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Revenue', '$142,500')
                ->description('32% increase from last month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([100, 110, 105, 120, 130, 125, 142]),
            Stat::make('Sales Growth', '+15.4%')
                ->description('Above Q2 target')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Customer Churn', '1.2%')
                ->description('Improved by 0.5%')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success'),
            Stat::make('Active Subscriptions', '1,245')
                ->description('45 new this week')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
