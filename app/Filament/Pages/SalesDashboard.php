<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class SalesDashboard extends BaseDashboard
{
    protected static ?string $title = 'Sales Dashboard';
    protected static string $routePath = 'sales-dashboard';
    protected static ?int $navigationSort = 2;
    protected static string | \UnitEnum | null $navigationGroup = 'Dashboards';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-presentation-chart-line';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user ? ($user->hasRole('sales') || $user->isAdmin()) : false;
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
            \App\Filament\Widgets\SalesSummaryWidget::class,
            \App\Filament\Widgets\SalesPipelineWidget::class,
            \App\Filament\Widgets\QuickLinksWidget::class,
            \App\Filament\Widgets\TopDealsWidget::class,
            \App\Filament\Widgets\UpcomingDeadlinesWidget::class,
            \App\Filament\Widgets\TodayTasksWidget::class,
        ];
    }
}
