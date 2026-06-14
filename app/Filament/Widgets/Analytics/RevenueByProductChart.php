<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\User;
use App\Services\Analytics\AnalyticsFilterData;
use App\Services\Analytics\AnalyticsService;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueByProductChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Revenue per Produk';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 3;

    protected function getData(): array
    {
        $rows = app(AnalyticsService::class)->revenueByProduct($this->user(), AnalyticsFilterData::fromArray($this->pageFilters ?? []));

        return [
            'datasets' => [[
                'label' => 'Revenue (IDR)',
                'data' => array_column($rows, 'revenue'),
                'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#8b5cf6', '#ef4444', '#64748b'],
            ]],
            'labels' => array_column($rows, 'product'),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    private function user(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}
