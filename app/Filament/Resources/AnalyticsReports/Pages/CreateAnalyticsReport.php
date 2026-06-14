<?php

namespace App\Filament\Resources\AnalyticsReports\Pages;

use App\Filament\Resources\AnalyticsReports\AnalyticsReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnalyticsReport extends CreateRecord
{
    protected static string $resource = AnalyticsReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['owner_id'] = auth()->id();
        $data['dataset'] = 'sales_performance';

        return $data;
    }
}
