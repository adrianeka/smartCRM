<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class CampaignPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Campaign Performance (Clicks vs Conversions)';
    protected static ?int $sort = 6;
    
    public static function canView(): bool
    {
        return !auth()->user()?->hasRole('Guest');
    }
    
    public function getColumnSpan(): int | string | array
    {
        return auth()->user()?->hasRole(['marketing', 'Marketing']) ? 4 : 3;
    }

    // Customize options to make it look premium
    protected ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'bottom',
            ],
        ],
        'elements' => [
            'line' => [
                'tension' => 0.4, // Smooth curve
            ],
        ],
    ];

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Clicks',
                    'data' => [1200, 1900, 3000, 5000, 4200, 6000, 7500],
                    'borderColor' => '#3b82f6', // blue-500
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Conversions',
                    'data' => [150, 230, 400, 650, 520, 800, 950],
                    'borderColor' => '#10b981', // emerald-500
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
