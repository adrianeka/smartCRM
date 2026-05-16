<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
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
            
            // 50/50 Split - Charts
            \App\Filament\Widgets\RevenueForecastChart::class,
            \App\Filament\Widgets\SalesPipelineWidget::class,
            
            // 50/50 Split - Tall Tables (Perfect Height Match)
            \App\Filament\Widgets\TopDealsWidget::class,
            \App\Filament\Widgets\UrgentTicketsWidget::class,
            
            // 75/25 Split - Short Widgets
            \App\Filament\Widgets\RecentActivitiesWidget::class,
            \App\Filament\Widgets\QuickLinksWidget::class,
        ];
    }
}
