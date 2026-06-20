<?php

namespace App\Filament\Exports;

use App\Models\Customer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class CustomerExporter extends Exporter
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('customer_code'),
            ExportColumn::make('full_name'),
            ExportColumn::make('job_title'),
            ExportColumn::make('email'),
            ExportColumn::make('website'),
            ExportColumn::make('phone'),
            ExportColumn::make('whatsapp'),
            ExportColumn::make('company_name'),
            ExportColumn::make('industry'),
            ExportColumn::make('identity_number'),
            ExportColumn::make('tax_number'),
            ExportColumn::make('gender'),
            ExportColumn::make('birth_date'),
            ExportColumn::make('address'),
            ExportColumn::make('city'),
            ExportColumn::make('province'),
            ExportColumn::make('postal_code'),
            ExportColumn::make('country'),
            ExportColumn::make('status'),
            ExportColumn::make('customer_type'),
            ExportColumn::make('source'),
            ExportColumn::make('lead_score'),
            ExportColumn::make('preferred_contact_method'),
            ExportColumn::make('last_contacted_at'),
            ExportColumn::make('next_follow_up_at'),
            ExportColumn::make('notes'),
            ExportColumn::make('is_favorite'),
            ExportColumn::make('assignedUser.name')
                ->label('assigned_to'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data pelanggan Anda telah selesai. Sebanyak '.Number::format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
