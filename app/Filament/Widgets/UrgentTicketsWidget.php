<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UrgentTicketsWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Urgent Tickets (Approaching SLA)';

    public function table(Table $table): Table
    {
        $urgentCustomers = Customer::whereNotNull('next_follow_up_at')
            ->orderBy('next_follow_up_at', 'asc')
            ->limit(5)
            ->get();

        if ($urgentCustomers->isEmpty()) {
            return $table
                ->records(fn (): array => [
                    ['id' => 'T-1042', 'customer' => 'PT Jaya Abadi', 'issue' => 'Failed login sistem', 'time_left' => '15 mnt'],
                    ['id' => 'T-1045', 'customer' => 'Budi Santoso', 'issue' => 'Pembayaran tidak diproses', 'time_left' => '45 mnt'],
                    ['id' => 'T-1046', 'customer' => 'CV Makmur', 'issue' => 'Kesalahan integrasi API', 'time_left' => '1j 10m'],
                    ['id' => 'T-1050', 'customer' => 'Sinar Mas Group', 'issue' => 'Laporan tidak dibuat', 'time_left' => '1j 30m'],
                    ['id' => 'T-1052', 'customer' => 'Tech Solutions', 'issue' => 'Server down warning', 'time_left' => '1j 45m'],
                ])
                ->columns([
                    TextColumn::make('issue')
                        ->label('Issue / Customer')
                        ->weight('bold')
                        ->description(fn ($record) => is_array($record) ? $record['customer'].' ('.$record['id'].')' : '')
                        ->limit(40),
                    TextColumn::make('time_left')
                        ->label('Sisa Waktu')
                        ->badge()
                        ->color('danger')
                        ->icon('heroicon-m-clock')
                        ->alignEnd(),
                ])
                ->paginated(false);
        }

        $records = [];
        foreach ($urgentCustomers as $customer) {
            $diff = now()->diff($customer->next_follow_up_at);
            if ($diff->invert) {
                $timeLeft = 'Terlambat';
            } else {
                $timeLeft = $diff->h > 0 ? "{$diff->h}j {$diff->i}m" : "{$diff->i} mnt";
            }

            $records[] = [
                'id' => 'C-'.$customer->customer_code,
                'customer' => $customer->company_name ?? $customer->full_name,
                'issue' => 'Follow-up: '.($customer->notes ?? 'Jadwalkan kontak'),
                'time_left' => $timeLeft,
            ];
        }

        return $table
            ->records(fn (): array => $records)
            ->columns([
                TextColumn::make('issue')
                    ->label('Issue / Customer')
                    ->weight('bold')
                    ->description(fn ($record) => is_array($record) ? $record['customer'].' ('.$record['id'].')' : '')
                    ->limit(40),
                TextColumn::make('time_left')
                    ->label('Sisa Waktu')
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-m-clock')
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
