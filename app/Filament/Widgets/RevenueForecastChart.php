<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;

class RevenueForecastChart extends ChartWidget
{
    protected ?string $heading = 'Revenue & Forecast (2026)';

    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 3;

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
        $hasData = Customer::count() > 0;
        $actualData = [];
        $forecastData = [];

        if ($hasData) {
            $currentYear = now()->year;
            for ($month = 1; $month <= 12; $month++) {
                $startOfMonth = now()->setDate($currentYear, $month, 1)->startOfMonth();
                $endOfMonth = now()->setDate($currentYear, $month, 1)->endOfMonth();
                $wonCount = Customer::whereIn('status', ['Active', 'Customer'])
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count();
                $actualData[] = $wonCount > 0 ? $wonCount * 15000 : 0;
                $forecastData[] = 40000 + (($month - 1) * 5000);
            }
        } else {
            $actualData = [45000, 52000, 48000, 61000, 75000, 82000, 0, 0, 0, 0, 0, 0];
            $forecastData = [40000, 45000, 50000, 55000, 65000, 70000, 80000, 90000, 100000, 110000, 120000, 130000];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Actual Revenue ($)',
                    'data' => $actualData,
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Forecast Target ($)',
                    'data' => $forecastData,
                    'backgroundColor' => 'rgba(156, 163, 175, 0.3)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
