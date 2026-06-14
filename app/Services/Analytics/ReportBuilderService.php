<?php

namespace App\Services\Analytics;

use App\Models\AnalyticsReportDefinition;
use App\Models\User;

class ReportBuilderService
{
    public function __construct(private readonly AnalyticsService $analytics) {}

    public static function columnOptions(): array
    {
        return [
            'sales_person' => 'Sales Person',
            'total_deals' => 'Total Deal',
            'won_deals' => 'Won Deals',
            'conversion_rate' => 'Conversion Rate (%)',
            'revenue' => 'Revenue (IDR)',
        ];
    }

    public function build(AnalyticsReportDefinition $report, User $user): array
    {
        $columns = $this->validColumns($report->columns ?? []);
        $rows = $this->analytics->salesPerformance(
            $user,
            AnalyticsFilterData::fromArray($report->filters ?? []),
        );

        return [
            'columns' => $columns,
            'headings' => array_map(
                fn (string $column): string => self::columnOptions()[$column],
                $columns,
            ),
            'rows' => array_map(
                fn (array $row): array => array_map(
                    fn (string $column): mixed => $row[$column],
                    $columns,
                ),
                $rows,
            ),
        ];
    }

    private function validColumns(array $columns): array
    {
        $validColumns = array_values(array_intersect($columns, array_keys(self::columnOptions())));

        return $validColumns ?: array_keys(self::columnOptions());
    }
}
