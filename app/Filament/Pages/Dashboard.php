<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getSubheading(): ?string
    {
        return 'Gambaran umum alur penjualan dan hubungan pelanggan Anda';
    }

    public static function canAccess(): bool
    {
        return true; // Allow everyone to hit the default /admin route
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function mount()
    {
        $user = auth()->user();
        if ($user && !$user->isAdmin()) {
            if ($user->hasRole('sales')) return redirect()->to(SalesDashboard::getUrl());
            if ($user->hasRole('marketing')) return redirect()->to(MarketingDashboard::getUrl());
            if ($user->hasRole('support')) return redirect()->to(SupportDashboard::getUrl());
            if ($user->hasRole('manager')) return redirect()->to(ManagerDashboard::getUrl());
        }
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
            
            // Charts Row
            \App\Filament\Widgets\RevenueForecastChart::class,
            \App\Filament\Widgets\SalesPipelineWidget::class,
            
            // Widgets Row
            \App\Filament\Widgets\RecentActivitiesWidget::class,
            \App\Filament\Widgets\QuickLinksWidget::class,

            // Full Width Tables
            \App\Filament\Widgets\TopDealsWidget::class,
            \App\Filament\Widgets\UrgentTicketsWidget::class,
        ];
    }
}
