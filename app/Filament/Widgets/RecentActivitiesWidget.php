<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Spatie\Activitylog\Models\Activity;

class RecentActivitiesWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activities-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    protected function getViewData(): array
    {
        $realActivities = Activity::latest()->limit(5)->get();

        $activities = [];

        foreach ($realActivities as $activity) {
            $activities[] = [
                'title' => ucfirst($activity->description),
                'description' => $activity->subject_type ? class_basename($activity->subject_type).' #'.$activity->subject_id : '',
                'time' => $activity->created_at->format('Y-m-d H:i'),
                'color' => match ($activity->event) {
                    'created' => '#22c55e',
                    'updated' => '#3b82f6',
                    'deleted' => '#ef4444',
                    default => '#f59e0b',
                },
            ];
        }

        if (empty($activities)) {
            $activities = [
                [
                    'title' => 'Product Demo',
                    'description' => 'Sarah Johnson - TechCorp',
                    'time' => '2026-04-21 14:00',
                    'color' => '#f59e0b',
                ],
                [
                    'title' => 'Follow-up Call',
                    'description' => 'Kevin Ardian - Global System',
                    'time' => '2026-04-21 10:30',
                    'color' => '#22c55e',
                ],
            ];
        }

        return [
            'activities' => $activities,
        ];
    }
}
