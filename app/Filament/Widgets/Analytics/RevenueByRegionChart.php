<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\User;
use App\Services\Analytics\AnalyticsFilterData;
use App\Services\Analytics\AnalyticsService;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueByRegionChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Revenue per Wilayah';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 3;

    protected function getData(): array
    {
        $rows = app(AnalyticsService::class)->revenueByRegion($this->user(), AnalyticsFilterData::fromArray($this->pageFilters ?? []));

        return [
            'datasets' => [[
                'label' => 'Revenue (IDR)',
                'data' => array_column($rows, 'revenue'),
                'backgroundColor' => '#3b82f6',
                'borderRadius' => 6,
            ]],
            'labels' => array_column($rows, 'region'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function user(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}
