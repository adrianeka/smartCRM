<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingDeadlinesWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 4;

    protected static ?string $heading = 'Upcoming Deadlines';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => [
                ['id' => 1, 'title' => 'Contact review meeting', 'date' => '2026-04-29', 'time' => '10:00'],
                ['id' => 2, 'title' => 'Quarterly sales report', 'date' => '2026-04-30', 'time' => 'EOD'],
                ['id' => 3, 'title' => 'Client presentation prep', 'date' => '2026-05-01', 'time' => '09:00'],
            ])
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('date')
                    ->description(function ($record) {
                        return is_array($record) ? $record['time'] : '';
                    })
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
