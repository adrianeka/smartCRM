<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TasksOverviewWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 6;

    protected static ?string $heading = 'Tasks Overview';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => [
                ['id' => 1, 'label' => 'Overdue Tasks', 'count' => 1, 'color' => 'danger', 'icon' => 'heroicon-m-exclamation-circle'],
                ['id' => 2, 'label' => 'Pending Tasks', 'count' => 2, 'color' => 'warning', 'icon' => 'heroicon-m-clock'],
                ['id' => 3, 'label' => 'Completed Today', 'count' => 2, 'color' => 'success', 'icon' => 'heroicon-m-check-circle'],
            ])
            ->columns([
                TextColumn::make('label')
                    ->label('Status')
                    ->badge()
                    ->placeholder('')
                    ->color(fn ($record): ?string => is_array($record) ? $record['color'] : 'gray')
                    ->icon(fn ($record): ?string => is_array($record) ? $record['icon'] : ''),
                TextColumn::make('count')
                    ->label('Total')
                    ->alignEnd()
                    ->placeholder('')
                    ->weight('bold'),
            ])
            ->paginated(false);
    }
}
