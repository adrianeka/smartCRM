<?php

namespace App\Console\Commands;

use App\Jobs\GenerateScheduledAnalyticsReport;
use App\Models\AnalyticsReportSchedule;
use Illuminate\Console\Command;

class SendScheduledAnalyticsReports extends Command
{
    protected $signature = 'analytics:send-scheduled-reports';

    protected $description = 'Dispatch due SmartCRM analytics report schedules';

    public function handle(): int
    {
        $count = 0;
        $now = now();

        AnalyticsReportSchedule::query()
            ->where('is_active', true)
            ->where('next_run_at', '<=', $now)
            ->pluck('id')
            ->each(function (int $scheduleId) use (&$count, $now): void {
                $schedule = AnalyticsReportSchedule::find($scheduleId);

                if (! $schedule) {
                    return;
                }

                $claimed = AnalyticsReportSchedule::query()
                    ->whereKey($scheduleId)
                    ->where('is_active', true)
                    ->where('next_run_at', '<=', $now)
                    ->update(['next_run_at' => $schedule->nextRunAfter($now)]);

                if (! $claimed) {
                    return;
                }

                GenerateScheduledAnalyticsReport::dispatch($scheduleId);
                $count++;
            });

        $this->info("Dispatched {$count} scheduled analytics report(s).");

        return self::SUCCESS;
    }
}
