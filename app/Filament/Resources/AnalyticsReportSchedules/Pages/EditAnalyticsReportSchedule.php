<?php

namespace App\Filament\Resources\AnalyticsReportSchedules\Pages;

use App\Filament\Resources\AnalyticsReportSchedules\AnalyticsReportScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAnalyticsReportSchedule extends EditRecord
{
    protected static string $resource = AnalyticsReportScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
