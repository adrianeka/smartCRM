<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class SupportDashboard extends BaseDashboard
{
    protected static ?string $title = 'Support Dashboard';
    protected static string $routePath = 'support-dashboard';
    protected static ?int $navigationSort = 4;
    protected static string | \UnitEnum | null $navigationGroup = 'Dashboards';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user ? ($user->hasRole('support') || $user->isAdmin()) : false;
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
            \App\Filament\Widgets\SupportStatsWidget::class,
            \App\Filament\Widgets\TicketsByPriorityChart::class,
            \App\Filament\Widgets\QuickLinksWidget::class,
            \App\Filament\Widgets\UrgentTicketsWidget::class,
        ];
    }
}
