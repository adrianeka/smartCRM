<?php

namespace App\Filament\Imports;

use App\Models\Customer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CustomerImporter extends Importer
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('customer_code')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('full_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('job_title')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('website')
                ->rules(['nullable', 'url', 'max:255']),
            ImportColumn::make('phone')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('whatsapp')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('company_name')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('industry')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('identity_number')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('tax_number')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('gender')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('birth_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('address')
                ->rules(['nullable', 'max:2000']),
            ImportColumn::make('city')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('province')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('postal_code')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('country')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('customer_type')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('source')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('lead_score')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0', 'max:100']),
            ImportColumn::make('preferred_contact_method')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('last_contacted_at')
                ->rules(['nullable', 'date']),
            ImportColumn::make('next_follow_up_at')
                ->rules(['nullable', 'date']),
            ImportColumn::make('notes')
                ->rules(['nullable', 'max:2000']),
            ImportColumn::make('is_favorite')
                ->boolean()
                ->rules(['nullable', 'boolean']),
            ImportColumn::make('assigned_user_id')
                ->rules(['nullable', 'exists:users,id']),
        ];
    }

    public function resolveRecord(): Customer
    {
        return Customer::firstOrNew([
            'customer_code' => $this->data['customer_code'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your customer import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
