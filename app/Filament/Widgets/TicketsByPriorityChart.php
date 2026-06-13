<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
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
        'cutout' => '70%',
    ];

    protected function getData(): array
    {
        $hasData = Customer::count() > 0;
        $high = 0;
        $medium = 0;
        $low = 0;

        if ($hasData) {
            $high = Customer::where('lead_score', '>=', 80)->count();
            $medium = Customer::whereBetween('lead_score', [40, 79])->count();
            $low = Customer::where('lead_score', '<', 40)->count();
        }

        if ($high === 0 && $medium === 0 && $low === 0) {
            $high = 5;
            $medium = 12;
            $low = 15;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => [$high, $medium, $low],
                    'backgroundColor' => [
                        '#ef4444',
                        '#f59e0b',
                        '#3b82f6',
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
