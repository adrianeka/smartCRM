<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\User;
use App\Services\Analytics\AnalyticsFilterData;
use App\Services\Analytics\AnalyticsService;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class SalesFunnelChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Sales Funnel';

    protected ?string $description = 'Distribusi deal pada setiap tahap penjualan.';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 2;

    protected ?array $options = [
        'indexAxis' => 'y',
        'plugins' => [
            'legend' => ['display' => false],
        ],
    ];

    protected function getData(): array
    {
        $rows = app(AnalyticsService::class)->salesFunnel($this->user(), AnalyticsFilterData::fromArray($this->pageFilters ?? []));

        return [
            'datasets' => [[
                'label' => 'Deals',
                'data' => array_column($rows, 'total'),
                'backgroundColor' => ['#f59e0b', '#fbbf24', '#60a5fa', '#34d399', '#10b981'],
                'borderRadius' => 6,
            ]],
            'labels' => array_column($rows, 'stage'),
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
