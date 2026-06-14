<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\User;
use App\Services\Analytics\AnalyticsFilterData;
use App\Services\Analytics\AnalyticsService;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsKpiOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $kpis = app(AnalyticsService::class)->kpis($this->user(), AnalyticsFilterData::fromArray($this->pageFilters ?? []));

        return [
            Stat::make('Total Revenue', $this->currency($kpis['total_revenue']))
                ->description($kpis['total_deals'].' deal pada periode terpilih')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Conversion Rate', $kpis['conversion_rate'].'%')
                ->description('Perbandingan deal won terhadap deal closed')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->icon('heroicon-o-arrow-path-rounded-square'),
            Stat::make('Churn Rate', $kpis['churn_rate'].'%')
                ->description('Pelanggan inactive pada data terpilih')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color($kpis['churn_rate'] > 10 ? 'danger' : 'warning')
                ->icon('heroicon-o-user-minus'),
            Stat::make('Customer Lifetime Value', $this->currency($kpis['customer_lifetime_value']))
                ->description('Rata-rata revenue per pelanggan won')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info')
                ->icon('heroicon-o-user-group'),
        ];
    }

    private function currency(float|int|string $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    private function user(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}
