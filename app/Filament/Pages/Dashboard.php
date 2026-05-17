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
        $user = auth()->user();

        if ($user?->hasRole('sales')) {
            return [
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\SalesSummaryWidget::class,
                \App\Filament\Widgets\SalesPipelineWidget::class,
                \App\Filament\Widgets\QuickLinksWidget::class,
                \App\Filament\Widgets\TopDealsWidget::class,
                \App\Filament\Widgets\UpcomingDeadlinesWidget::class,
                \App\Filament\Widgets\TodayTasksWidget::class,
            ];
        }

        if ($user?->hasRole('marketing')) {
            return [
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\MarketingStatsWidget::class,
                \App\Filament\Widgets\CampaignPerformanceChart::class,
                \App\Filament\Widgets\QuickLinksWidget::class,
                \App\Filament\Widgets\RecentCampaignsWidget::class,
            ];
        }

        if ($user?->hasRole('support')) {
            return [
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\SupportStatsWidget::class,
                \App\Filament\Widgets\TicketsByPriorityChart::class,
                \App\Filament\Widgets\QuickLinksWidget::class,
                \App\Filament\Widgets\UrgentTicketsWidget::class,
            ];
        }

        if ($user?->hasRole('manager')) {
            return [
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\CompanyPerformanceWidget::class,
                \App\Filament\Widgets\RevenueForecastChart::class,
                \App\Filament\Widgets\CampaignPerformanceChart::class,
                \App\Filament\Widgets\TicketsByPriorityChart::class,
                \App\Filament\Widgets\QuickLinksWidget::class,
                \App\Filament\Widgets\TopDealsWidget::class,
                \App\Filament\Widgets\RecentActivitiesWidget::class,
            ];
        }
        
        return [
            \App\Filament\Widgets\WelcomeWidget::class,
            \App\Filament\Widgets\CompanyPerformanceWidget::class,
            \App\Filament\Widgets\RevenueForecastChart::class,
            \App\Filament\Widgets\SalesPipelineWidget::class,
            \App\Filament\Widgets\TicketsByPriorityChart::class,
            \App\Filament\Widgets\QuickLinksWidget::class,
            \App\Filament\Widgets\TopDealsWidget::class,
            \App\Filament\Widgets\UrgentTicketsWidget::class,
            \App\Filament\Widgets\RecentActivitiesWidget::class,
        ];
    }
}
