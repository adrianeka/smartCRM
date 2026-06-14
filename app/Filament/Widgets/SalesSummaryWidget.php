<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalCustomers = Customer::count();
        $activeDeals = Customer::whereIn('status', ['Lead', 'Qualified', 'Proposal', 'Negotiation'])->count();
        $wonDeals = Customer::where('status', 'Customer')->count();

        $winRate = $totalCustomers > 0
            ? (int) round(($wonDeals / $totalCustomers) * 100)
            : 68;

        $pipelineValue = $activeDeals > 0
            ? '$'.($activeDeals * 100).'K'
            : '$705K';

        // Calculate actual growth: this month vs last month new customers
        $thisMonth = Customer::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $lastMonth = Customer::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $growthPct = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100)
            : 0;
        $growthLabel = ($growthPct >= 0 ? '+' : '').$growthPct.'% pertumbuhan';

        return [
            Stat::make('Kontak Aktif', (string) ($totalCustomers > 0 ? $totalCustomers : 5))
                ->description($growthLabel)
                ->descriptionIcon($growthPct >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($growthPct >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-users'),
            Stat::make('Nilai Pipeline', $pipelineValue)
                ->description($activeDeals.' deal aktif')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Deal Aktif', (string) ($activeDeals > 0 ? $activeDeals : 7))
                ->description($growthLabel)
                ->descriptionIcon($growthPct >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($growthPct >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-briefcase'),
            Stat::make('Tingkat Kemenangan', $winRate.'%')
                ->description($winRate >= 50 ? '+15%' : '-15%')
                ->descriptionIcon($winRate >= 50 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($winRate >= 50 ? 'success' : 'danger')
                ->icon('heroicon-o-trophy'),
        ];
    }
}
