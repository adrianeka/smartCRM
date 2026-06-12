<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TicketsByPriorityChart extends ChartWidget
{
    protected ?string $heading = 'Tickets by Priority';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 4;

    protected ?string $maxHeight = '375px';

    protected ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'right',
            ],
        ],
        'cutout' => '70%', // makes it a doughnut chart
    ];

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => [5, 12, 15],
                    'backgroundColor' => [
                        '#ef4444', // danger (High)
                        '#f59e0b', // warning (Medium)
                        '#3b82f6', // info (Low)
                    ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['High', 'Medium', 'Low'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
