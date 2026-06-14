<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CampaignPerformanceChart;
use App\Filament\Widgets\CompanyPerformanceWidget;
use App\Filament\Widgets\MarketingStatsWidget;
use App\Filament\Widgets\QuickLinksWidget;
use App\Filament\Widgets\RecentActivitiesWidget;
use App\Filament\Widgets\RecentCampaignsWidget;
use App\Filament\Widgets\RevenueForecastChart;
use App\Filament\Widgets\SalesPipelineWidget;
use App\Filament\Widgets\SalesSummaryWidget;
use App\Filament\Widgets\SupportStatsWidget;
use App\Filament\Widgets\TasksOverviewWidget;
use App\Filament\Widgets\TicketsByPriorityChart;
use App\Filament\Widgets\TodayTasksWidget;
use App\Filament\Widgets\TopDealsWidget;
use App\Filament\Widgets\UpcomingDeadlinesWidget;
use App\Filament\Widgets\UrgentTicketsWidget;
use App\Filament\Widgets\WebhookStats;
use App\Filament\Widgets\WelcomeWidget;
use App\Models\User;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getSubheading(): ?string
    {
        return 'Gambaran umum alur penjualan dan hubungan pelanggan Anda';
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 6,
        ];
    }

    public function getWidgets(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        if ($user?->hasRole(['sales', 'Sales'])) {
            return [
                WelcomeWidget::class,
                SalesSummaryWidget::class,
                SalesPipelineWidget::class,
                QuickLinksWidget::class,
                TopDealsWidget::class,
                UpcomingDeadlinesWidget::class,
                TodayTasksWidget::class,
                TasksOverviewWidget::class,
            ];
        }

        if ($user?->hasRole(['marketing', 'Marketing'])) {
            return [
                WelcomeWidget::class,
                MarketingStatsWidget::class,
                CampaignPerformanceChart::class,
                QuickLinksWidget::class,
                RecentCampaignsWidget::class,
            ];
        }

        if ($user?->hasRole(['support', 'Support'])) {
            return [
                WelcomeWidget::class,
                SupportStatsWidget::class,
                TicketsByPriorityChart::class,
                QuickLinksWidget::class,
                UrgentTicketsWidget::class,
                TasksOverviewWidget::class,
            ];
        }

        if ($user?->hasRole(['manager', 'Manager/Analyst'])) {
            // WebhookStats is removed from Manager's dashboard as it is technical API logs summary
            return [
                WelcomeWidget::class,
                CompanyPerformanceWidget::class,
                RevenueForecastChart::class,
                CampaignPerformanceChart::class,
                TicketsByPriorityChart::class,
                QuickLinksWidget::class,
                TopDealsWidget::class,
                RecentActivitiesWidget::class,
                TasksOverviewWidget::class,
            ];
        }

        // Default widgets (e.g. Super Admin)
        return [
            WelcomeWidget::class,
            WebhookStats::class,
            CompanyPerformanceWidget::class,
            RevenueForecastChart::class,
            SalesPipelineWidget::class,
            TicketsByPriorityChart::class,
            QuickLinksWidget::class,
            TopDealsWidget::class,
            TodayTasksWidget::class,
            UpcomingDeadlinesWidget::class,
            UrgentTicketsWidget::class,
            RecentActivitiesWidget::class,
            TasksOverviewWidget::class,
        ];
    }
}
