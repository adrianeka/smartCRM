<?php

namespace App\Filament\Resources\Customers\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pelanggan', Customer::count())
                ->description('Semua data pelanggan di SmartCRM')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Lead Aktif', Customer::where('status', 'Lead')->count())
                ->description('Calon pelanggan yang perlu follow-up')
                ->icon('heroicon-o-sparkles')
                ->color('warning'),

            Stat::make('Baru Bulan Ini', Customer::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count())
                ->description('Pertumbuhan pelanggan bulan ini')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Belum Ditugaskan', Customer::whereNull('assigned_user_id')->count())
                ->description('Belum punya PIC Sales/Admin')
                ->icon('heroicon-o-user-minus')
                ->color('danger'),
        ];
    }
}
