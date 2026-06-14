<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\User;
use App\Services\Analytics\AnalyticsFilterData;
use App\Services\Analytics\AnalyticsService;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueTrendChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Tren Revenue';

    protected ?string $description = 'Revenue dari deal berstatus Won.';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 4;

    protected function getData(): array
    {
        $rows = app(AnalyticsService::class)->revenueTrend($this->user(), AnalyticsFilterData::fromArray($this->pageFilters ?? []));

        return [
            'datasets' => [[
                'label' => 'Revenue (IDR)',
                'data' => array_column($rows, 'revenue'),
                'borderColor' => '#f59e0b',
                'backgroundColor' => 'rgba(245, 158, 11, 0.18)',
                'fill' => true,
                'tension' => 0.35,
            ]],
            'labels' => array_column($rows, 'label'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function user(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}
