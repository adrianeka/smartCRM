<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Exports\CustomerExporter;
use App\Filament\Imports\CustomerImporter;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Customers\Widgets\CustomerStatsOverview;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerStatsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(CustomerImporter::class)
                ->label('Import CSV/Excel')
                ->color('info')
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Marketing']) ?? false),

            ExportAction::make()
                ->exporter(CustomerExporter::class)
                ->label('Export Data')
                ->color('success')
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Marketing', 'Manager/Analyst']) ?? false),

            Action::make('download_excel')
                ->label('Unduh Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->url('/api/v1/customers/export/excel')
                ->openUrlInNewTab()
                ->color('success')
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Marketing', 'Manager/Analyst']) ?? false),

            Action::make('download_csv')
                ->label('Unduh CSV')
                ->icon('heroicon-o-document-arrow-down')
                ->url('/api/v1/customers/export/csv')
                ->openUrlInNewTab()
                ->color('gray')
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Marketing', 'Manager/Analyst']) ?? false),

            Action::make('check_duplicates')
                ->label('Check Duplicates')
                ->icon('heroicon-o-magnifying-glass')
                ->url(CustomerResource::getUrl('duplicates'))
                ->color('warning')
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Sales', 'Manager/Analyst']) ?? false),

            CreateAction::make()
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Sales', 'Marketing']) ?? false),
        ];
    }
}
