<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\User;
use App\Services\Analytics\AnalyticsFilterData;
use App\Services\Analytics\AnalyticsService;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class SalesPerformanceWidget extends Widget
{
    use InteractsWithPageFilters;

    protected string $view = 'filament.widgets.analytics.sales-performance-widget';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'rows' => app(AnalyticsService::class)->salesPerformance(
                $this->user(),
                AnalyticsFilterData::fromArray($this->pageFilters ?? []),
            ),
        ];
    }

    private function user(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}
