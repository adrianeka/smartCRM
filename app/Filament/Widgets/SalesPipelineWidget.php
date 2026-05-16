<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SalesPipelineWidget extends Widget
{
    protected string $view = 'filament.widgets.sales-pipeline-widget';
    protected int | string | array $columnSpan = 3;
    protected static ?int $sort = 5;

    protected function getViewData(): array
    {
        return [
            'pipelines' => [
                ['stage' => 'Negotiation', 'deals' => 2, 'percentage' => 30],
                ['stage' => 'Proposal', 'deals' => 1, 'percentage' => 50],
                ['stage' => 'Qualified', 'deals' => 5, 'percentage' => 70],
                ['stage' => 'Lead', 'deals' => 7, 'percentage' => 100],
            ]
        ];
    }
}
