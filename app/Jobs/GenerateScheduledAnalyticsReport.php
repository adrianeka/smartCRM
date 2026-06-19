<?php

namespace App\Jobs;

use App\Mail\ScheduledAnalyticsReportMail;
use App\Models\AnalyticsReportRun;
use App\Models\AnalyticsReportSchedule;
use App\Services\Analytics\ReportExportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class GenerateScheduledAnalyticsReport implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $scheduleId) {}

    public function handle(ReportExportService $exporter): void
    {
        $schedule = AnalyticsReportSchedule::query()
            ->with(['reportDefinition', 'createdBy'])
            ->findOrFail($this->scheduleId);

        if (! $schedule->is_active) {
            return;
        }

        $run = AnalyticsReportRun::create([
            'report_definition_id' => $schedule->report_definition_id,
            'requested_by' => $schedule->created_by,
            'schedule_id' => $schedule->id,
            'format' => $schedule->format,
            'status' => 'processing',
        ]);

        try {
            $path = $exporter->store($schedule->reportDefinition, $schedule->createdBy, $schedule->format);

            $run->update([
                'status' => 'completed',
                'file_disk' => 'local',
                'file_path' => $path,
                'completed_at' => now(),
            ]);

            foreach ($schedule->recipients as $recipient) {
                Mail::to($recipient)->send(new ScheduledAnalyticsReportMail(
                    report: $schedule->reportDefinition,
                    path: $path,
                    format: $schedule->format,
                ));
            }

            $schedule->update([
                'last_run_at' => now(),
                'next_run_at' => $schedule->nextRunAfter(now()),
            ]);
        } catch (Throwable $exception) {
            $run->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
