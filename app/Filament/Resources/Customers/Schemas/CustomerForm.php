<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput; // 1. Kita import Textarea yang asli di sini Kel
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_code')
                    ->required(),
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('company_name'),
                TextInput::make('status')
                    ->required()
                    ->default('Lead'),

                // 2. Kita selipkan komponen Textarea di paling bawah array components
                Textarea::make('custom_fields')
                    ->label('Additional Information (JSON Format)')
                    ->placeholder('{"Instagram": "@budi_jaya", "Kategori": "VIP"}')
                    ->rows(3)
                    ->helperText('Masukkan data tambahan dengan format JSON kustom jika diperlukan.'),
            ]);
    }
}
