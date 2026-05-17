<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class TodayTasksWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;
    protected static ?string $heading = "Today's Tasks";

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => [
                ['id' => 1, 'title' => 'Product demo with TechCorp', 'time' => '2026-04-28 / 14:00', 'priority' => 'high'],
                ['id' => 2, 'title' => 'Follow-up call with Global Systems', 'time' => '2026-04-28 / 15:30', 'priority' => 'medium'],
                ['id' => 3, 'title' => 'Send proposal to Mann Co.', 'time' => '2026-04-28 / 17:00', 'priority' => 'high'],
                ['id' => 4, 'title' => 'Update pipeline status', 'time' => '2026-04-28 / 14:00', 'priority' => 'low'],
            ])
            ->columns([
                TextColumn::make('title')
                    ->description(function ($record) {
                        return is_array($record) ? $record['time'] : '';
                    })
                    ->icon('heroicon-o-clock'),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'info',
                        default => 'gray',
                    })
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
