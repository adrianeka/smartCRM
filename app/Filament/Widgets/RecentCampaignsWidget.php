<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class RecentCampaignsWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = "Recent Campaigns";

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => [
                ['id' => 1, 'name' => 'Summer Promo 2026', 'status' => 'Running', 'leads' => 340],
                ['id' => 2, 'name' => 'Webinar Follow-up', 'status' => 'Scheduled', 'leads' => 0],
                ['id' => 3, 'name' => 'Q1 Newsletter', 'status' => 'Completed', 'leads' => 1205],
                ['id' => 4, 'name' => 'Product X Launch', 'status' => 'Draft', 'leads' => 0],
            ])
            ->columns([
                TextColumn::make('name')
                    ->weight('bold'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Running' => 'success',
                        'Scheduled' => 'warning',
                        'Completed' => 'info',
                        'Draft' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('leads')
                    ->label('Leads')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
