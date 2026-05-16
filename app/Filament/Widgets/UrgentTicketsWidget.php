<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class UrgentTicketsWidget extends BaseWidget
{
    protected static ?int $sort = 9;
    protected int | string | array $columnSpan = 2;
    protected static ?string $heading = "Urgent Tickets (Near SLA)";

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => [
                ['id' => 'T-1042', 'customer' => 'PT Jaya Abadi', 'issue' => 'System login failure', 'time_left' => '15 mins'],
                ['id' => 'T-1045', 'customer' => 'Budi Santoso', 'issue' => 'Payment not processed', 'time_left' => '45 mins'],
                ['id' => 'T-1046', 'customer' => 'CV Makmur', 'issue' => 'API integration error', 'time_left' => '1h 10m'],
                ['id' => 'T-1050', 'customer' => 'Sinar Mas Group', 'issue' => 'Report not generating', 'time_left' => '1h 30m'],
            ])
            ->columns([
                TextColumn::make('id')
                    ->label('Ticket ID')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('customer')
                    ->searchable(),
                TextColumn::make('issue')
                    ->limit(30),
                TextColumn::make('time_left')
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-m-clock'),
            ])
            ->paginated(false);
    }
}
