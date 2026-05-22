<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SalesPipelineWidget extends Widget
{
    protected string $view = 'filament.widgets.sales-pipeline-widget';
    protected static ?int $sort = 5;

    public function getColumnSpan(): int | string | array
    {
        return auth()->user()?->hasRole('sales') ? 4 : 3;
    }

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
