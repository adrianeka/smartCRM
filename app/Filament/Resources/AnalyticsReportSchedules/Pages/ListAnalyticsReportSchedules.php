<?php

namespace App\Filament\Resources\AnalyticsReportSchedules\Pages;

use App\Filament\Resources\AnalyticsReportSchedules\AnalyticsReportScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnalyticsReportSchedules extends ListRecords
{
    protected static string $resource = AnalyticsReportScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Schedule'),
        ];
    }
}
