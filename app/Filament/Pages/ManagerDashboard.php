<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class ManagerDashboard extends BaseDashboard
{
    protected static ?string $title = 'Manager / Analyst Dashboard';
    protected static string $routePath = 'manager-dashboard';
    protected static ?int $navigationSort = 1;
    protected static string | \UnitEnum | null $navigationGroup = 'Dashboards';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar-square';

    public function getSubheading(): ?string
    {
        return 'Gambaran umum alur penjualan dan hubungan pelanggan Anda';
    }

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 6,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\WelcomeWidget::class,
            \App\Filament\Widgets\CompanyPerformanceWidget::class,
            \App\Filament\Widgets\RevenueForecastChart::class,
            \App\Filament\Widgets\CampaignPerformanceChart::class,
            \App\Filament\Widgets\SalesPipelineWidget::class,
            \App\Filament\Widgets\TicketsByPriorityChart::class,
            \App\Filament\Widgets\RecentActivitiesWidget::class,
            \App\Filament\Widgets\QuickLinksWidget::class,
            \App\Filament\Widgets\TopDealsWidget::class,
        ];
    }
}
