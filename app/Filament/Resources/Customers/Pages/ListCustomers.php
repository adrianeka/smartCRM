<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Exports\CustomerExporter;
use App\Filament\Imports\CustomerImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(CustomerImporter::class)
                ->label('Import CSV/Excel')
                ->color('info'),

            ExportAction::make()
                ->exporter(CustomerExporter::class)
                ->label('Export Data')
                ->color('success'),

            CreateAction::make(),
        ];
    }
}