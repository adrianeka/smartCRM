<?php

namespace App\Filament\Resources\WebhookLogs;

use App\Filament\Resources\WebhookLogs\Pages\CreateWebhookLog;
use App\Filament\Resources\WebhookLogs\Pages\EditWebhookLog;
use App\Filament\Resources\WebhookLogs\Pages\ListWebhookLogs;
use App\Filament\Resources\WebhookLogs\Pages\ViewWebhookLog;
use App\Filament\Resources\WebhookLogs\Schemas\WebhookLogForm;
use App\Filament\Resources\WebhookLogs\Schemas\WebhookLogInfolist;
use App\Filament\Resources\WebhookLogs\Tables\WebhookLogsTable;
use App\Models\WebhookLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;


class WebhookLogResource extends Resource
{
    protected static ?string $model = WebhookLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'event_type';

    public static function form(Schema $schema): Schema
    {
        return WebhookLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WebhookLogInfolist::configure($schema);
    }

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('id')
                ->sortable(),

            TextColumn::make('event_type')
                ->searchable(),

            TextColumn::make('source_module')
                ->searchable(),

            TextColumn::make('status')
                ->badge(),

            TextColumn::make('created_at')
                ->dateTime(),
        ])
        ->defaultSort('id', 'desc');
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebhookLogs::route('/'),
            'create' => CreateWebhookLog::route('/create'),
            'view' => ViewWebhookLog::route('/{record}'),
            'edit' => EditWebhookLog::route('/{record}/edit'),
        ];
    }
}
