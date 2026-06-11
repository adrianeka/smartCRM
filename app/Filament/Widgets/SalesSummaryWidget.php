<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalCustomers = \App\Models\Customer::count();
        $activeDeals = \App\Models\Customer::whereIn('status', ['Lead', 'Qualified', 'Proposal', 'Negotiation'])->count();
        $wonDeals = \App\Models\Customer::where('status', 'Customer')->count();

        $winRate = $totalCustomers > 0 
            ? (int) round(($wonDeals / $totalCustomers) * 100) 
            : 68;

        $pipelineValue = $activeDeals > 0 
            ? '$' . ($activeDeals * 100) . 'K' 
            : '$705K';

        return [
            Stat::make('Active Contacts', (string) ($totalCustomers > 0 ? $totalCustomers : 5))
                ->description('+25%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-users'),
            Stat::make('Pipeline Value', $pipelineValue)
                ->description('+25%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Active Deals', (string) ($activeDeals > 0 ? $activeDeals : 7))
                ->description('+25%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-briefcase'),
            Stat::make('Win rate', $winRate . '%')
                ->description($winRate >= 50 ? '+15%' : '-15%')
                ->descriptionIcon($winRate >= 50 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($winRate >= 50 ? 'success' : 'danger')
                ->icon('heroicon-o-trophy'),
        ];
    }
}
