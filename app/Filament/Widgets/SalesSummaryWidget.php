<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Active Contacts', '5')
                ->description('+25%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-users'),
            Stat::make('Pipeline Value', '$705K')
                ->description('+25%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Active Deals', '7')
                ->description('+25%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-briefcase'),
            Stat::make('Win rate', '68%')
                ->description('-15%')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->icon('heroicon-o-trophy'),
        ];
    }
}
