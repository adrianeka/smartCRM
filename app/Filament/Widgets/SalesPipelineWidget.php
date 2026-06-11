<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\Widget;

class SalesPipelineWidget extends Widget
{
    protected string $view = 'filament.widgets.sales-pipeline-widget';

    protected static ?int $sort = 5;

    public function getColumnSpan(): int|string|array
    {
        return auth()->user()?->hasRole(['sales', 'Sales']) ? 4 : 3;
    }

    protected function getViewData(): array
    {
        $stages = ['Negotiation', 'Proposal', 'Qualified', 'Lead'];

        $pipelines = [];

        foreach ($stages as $stage) {
            $count = Customer::where('status', $stage)->count();
            $pipelines[] = [
                'stage' => $stage,
                'deals' => $count,
                'percentage' => match ($stage) {
                    'Negotiation' => 30,
                    'Proposal' => 50,
                    'Qualified' => 70,
                    'Lead' => 100,
                    default => 0,
                },
            ];
        }

        $totalDeals = array_sum(array_column($pipelines, 'deals'));

        if ($totalDeals === 0) {
            $pipelines = [
                ['stage' => 'Negotiation', 'deals' => 2, 'percentage' => 30],
                ['stage' => 'Proposal', 'deals' => 1, 'percentage' => 50],
                ['stage' => 'Qualified', 'deals' => 5, 'percentage' => 70],
                ['stage' => 'Lead', 'deals' => 7, 'percentage' => 100],
            ];
        }

        return [
            'pipelines' => $pipelines,
        ];
    }
}
