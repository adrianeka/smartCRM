<?php

namespace Database\Seeders;

use App\Models\AnalyticsReportDefinition;
use App\Models\AnalyticsReportSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnalyticsReportDemoSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::role('Manager/Analyst')->first();

        if (! $manager) {
            return;
        }

        $report = AnalyticsReportDefinition::updateOrCreate(
            ['owner_id' => $manager->id, 'name' => 'Monthly Sales Performance'],
            [
                'dataset' => 'sales_performance',
                'columns' => ['sales_person', 'total_deals', 'won_deals', 'conversion_rate', 'revenue'],
                'filters' => [
                    'from' => now()->startOfYear()->toDateString(),
                    'until' => now()->endOfYear()->toDateString(),
                ],
                'branding' => [
                    'title' => 'SmartCRM Analytics',
                    'subtitle' => 'Monthly Sales Performance',
                    'primary_color' => '#f59e0b',
                ],
                'visibility' => 'team',
            ],
        );

        AnalyticsReportSchedule::updateOrCreate(
            ['report_definition_id' => $report->id, 'created_by' => $manager->id],
            [
                'frequency' => 'monthly',
                'format' => 'pdf',
                'recipients' => [$manager->email],
                'next_run_at' => now()->addMonth()->startOfMonth()->setTime(8, 0),
                'is_active' => false,
            ],
        );
    }
}
