<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RevenueForecastChart extends ChartWidget
{
    protected ?string $heading = 'Revenue & Forecast (2026)';
    protected static ?int $sort = 11;
    protected int | string | array $columnSpan = 2;

    protected ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'bottom',
            ],
        ],
    ];

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Actual Revenue ($)',
                    'data' => [45000, 52000, 48000, 61000, 75000, 82000],
                    'backgroundColor' => '#10b981', // emerald-500
                ],
                [
                    'label' => 'Forecast Target ($)',
                    'data' => [40000, 45000, 50000, 55000, 65000, 70000, 80000, 90000, 100000, 110000, 120000, 130000],
                    'backgroundColor' => 'rgba(156, 163, 175, 0.3)', // gray-400
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
