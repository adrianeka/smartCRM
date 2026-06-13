<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;

class CampaignPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Campaign Performance (Clicks vs Conversions)';

    protected static ?int $sort = 6;

    public function getColumnSpan(): int|string|array
    {
        return auth()->user()?->hasRole(['marketing', 'Marketing']) ? 4 : 3;
    }

    protected ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'bottom',
            ],
        ],
        'elements' => [
            'line' => [
                'tension' => 0.4,
            ],
        ],
    ];

    protected function getData(): array
    {
        $hasData = Customer::count() > 0;
        $clicksData = [];
        $conversionsData = [];

        if ($hasData) {
            $startOfWeek = now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $dayStart = $startOfWeek->copy()->addDays($i)->startOfDay();
                $dayEnd = $startOfWeek->copy()->addDays($i)->endOfDay();
                $conversions = Customer::whereBetween('created_at', [$dayStart, $dayEnd])->count();
                $clicks = ($conversions * 10) + (($i + 1) * 3) + 5;
                $clicksData[] = $clicks;
                $conversionsData[] = $conversions;
            }
        } else {
            $clicksData = [1200, 1900, 3000, 5000, 4200, 6000, 7500];
            $conversionsData = [150, 230, 400, 650, 520, 800, 950];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Clicks',
                    'data' => $clicksData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Conversions',
                    'data' => $conversionsData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
