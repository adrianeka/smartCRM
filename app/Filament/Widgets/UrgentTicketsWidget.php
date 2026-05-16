<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class UrgentTicketsWidget extends BaseWidget
{
    protected static ?int $sort = 9;
    protected int | string | array $columnSpan = 3;
    protected static ?string $heading = "Urgent Tickets (Near SLA)";

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => [
                ['id' => 'T-1042', 'customer' => 'PT Jaya Abadi', 'issue' => 'System login failure', 'time_left' => '15 mins'],
                ['id' => 'T-1045', 'customer' => 'Budi Santoso', 'issue' => 'Payment not processed', 'time_left' => '45 mins'],
                ['id' => 'T-1046', 'customer' => 'CV Makmur', 'issue' => 'API integration error', 'time_left' => '1h 10m'],
                ['id' => 'T-1050', 'customer' => 'Sinar Mas Group', 'issue' => 'Report not generating', 'time_left' => '1h 30m'],
                ['id' => 'T-1052', 'customer' => 'Tech Solutions', 'issue' => 'Server downtime alert', 'time_left' => '1h 45m'],
            ])
            ->columns([
                TextColumn::make('issue')
                    ->label('Issue / Customer')
                    ->weight('bold')
                    ->description(fn ($record) => is_array($record) ? $record['customer'] . ' (' . $record['id'] . ')' : '')
                    ->limit(40),
                TextColumn::make('time_left')
                    ->label('Time Left')
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-m-clock')
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
