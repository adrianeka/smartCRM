<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class MarketingDashboard extends BaseDashboard
{
    protected static ?string $title = 'Marketing Dashboard';
    protected static string $routePath = 'marketing-dashboard';
    protected static ?int $navigationSort = 3;
    protected static string | \UnitEnum | null $navigationGroup = 'Dashboards';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user ? ($user->hasRole('marketing') || $user->isAdmin()) : false;
    }

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
            \App\Filament\Widgets\MarketingStatsWidget::class,
            \App\Filament\Widgets\CampaignPerformanceChart::class,
            \App\Filament\Widgets\QuickLinksWidget::class,
            \App\Filament\Widgets\RecentCampaignsWidget::class,
        ];
    }
}
