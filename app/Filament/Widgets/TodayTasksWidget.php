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
                    ['id' => 1, 'title' => 'Demo produk dengan TechCorp', 'time' => '2026-04-28 / 14:00', 'priority' => 'tinggi'],
                    ['id' => 2, 'title' => 'Panggilan follow-up dengan Global Systems', 'time' => '2026-04-28 / 15:30', 'priority' => 'sedang'],
                    ['id' => 3, 'title' => 'Kirim proposal ke Mann Co.', 'time' => '2026-04-28 / 17:00', 'priority' => 'tinggi'],
                    ['id' => 4, 'title' => 'Perbarui status pipeline', 'time' => '2026-04-28 / 14:00', 'priority' => 'rendah'],
                ])
                ->columns([
                    TextColumn::make('title')
                        ->label('Judul Tugas')
                        ->description(fn ($record) => $record['time'])
                        ->icon('heroicon-o-clock'),
                    TextColumn::make('priority')
                        ->label('Prioritas')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'tinggi' => 'danger',
                            'sedang' => 'warning',
                            'rendah' => 'info',
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
