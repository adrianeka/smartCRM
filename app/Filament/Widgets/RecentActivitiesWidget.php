<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class RecentActivitiesWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activities-widget';
    protected int | string | array $columnSpan = 4;
    protected static ?int $sort = 5;

    protected function getViewData(): array
    {
        return [
            'activities' => [
                [
                    'title' => 'Product Demo', 
                    'description' => 'Sarah Johnson - TechCorp', 
                    'time' => '2026-04-21 14:00',
                    'color' => '#f59e0b' // amber/orange
                ],
                [
                    'title' => 'Follow-up Call', 
                    'description' => 'Kevin Ardian - Global System', 
                    'time' => '2026-04-21 10:30',
                    'color' => '#22c55e' // green
                ],
            ]
        ];
    }
}
