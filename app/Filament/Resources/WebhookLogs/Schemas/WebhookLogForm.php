<?php

namespace App\Filament\Resources\WebhookLogs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WebhookLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('event_type')
                    ->required(),
                TextInput::make('source_module')
                    ->required(),
                TextInput::make('target_url')
                    ->url()
                    ->default(null),
                TextInput::make('status_code')
                    ->numeric()
                    ->default(null),
                Textarea::make('payload')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('response')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'success' => 'Success', 'failed' => 'Failed'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
