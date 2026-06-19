<?php

namespace App\Filament\Resources\AnalyticsReportSchedules\Pages;

use App\Filament\Resources\AnalyticsReportSchedules\AnalyticsReportScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnalyticsReportSchedule extends CreateRecord
{
    protected static string $resource = AnalyticsReportScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
