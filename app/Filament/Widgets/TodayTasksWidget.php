<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayTasksWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected static ?string $heading = 'Tugas Hari Ini';

    public function table(Table $table): Table
    {
        $todayTasks = Customer::query()
            ->whereDate('next_follow_up_at', Carbon::today())
            ->orderBy('next_follow_up_at', 'asc')
            ->get();

        if ($todayTasks->isEmpty()) {
            return $table
                ->records(fn (): array => [
                    ['id' => 1, 'title' => 'Product demo with TechCorp', 'time' => '2026-04-28 / 14:00', 'priority' => 'high'],
                    ['id' => 2, 'title' => 'Follow-up call with Global Systems', 'time' => '2026-04-28 / 15:30', 'priority' => 'medium'],
                    ['id' => 3, 'title' => 'Send proposal to Mann Co.', 'time' => '2026-04-28 / 17:00', 'priority' => 'high'],
                    ['id' => 4, 'title' => 'Update pipeline status', 'time' => '2026-04-28 / 14:00', 'priority' => 'low'],
                ])
                ->columns([
                    TextColumn::make('title')
                        ->description(fn ($record) => $record['time'])
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

        return $table
            ->query(
                fn () => Customer::query()
                    ->whereDate('next_follow_up_at', Carbon::today())
                    ->orderBy('next_follow_up_at', 'asc')
            )
            ->columns([
                TextColumn::make('full_name')
                    ->label('Tugas / Pelanggan')
                    ->description(fn ($record) => $record->notes ?? 'Perlu follow-up')
                    ->icon('heroicon-o-clock'),
                TextColumn::make('status')
                    ->label('Prioritas / Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lead' => 'info',
                        'Qualified' => 'warning',
                        'Proposal', 'Negotiation' => 'danger',
                        default => 'gray',
                    })
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
