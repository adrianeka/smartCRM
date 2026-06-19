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

    protected static ?string $heading = 'Urgent Tickets (Near SLA)';

    public function table(Table $table): Table
    {
        $urgentCustomers = Customer::whereNotNull('next_follow_up_at')
            ->orderBy('next_follow_up_at', 'asc')
            ->limit(5)
            ->get();

        if ($urgentCustomers->isEmpty()) {
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
                        ->description(fn ($record) => is_array($record) ? $record['customer'].' ('.$record['id'].')' : '')
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

        $records = [];
        foreach ($urgentCustomers as $customer) {
            $diff = now()->diff($customer->next_follow_up_at);
            if ($diff->invert) {
                $timeLeft = 'Overdue';
            } else {
                $timeLeft = $diff->h > 0 ? "{$diff->h}h {$diff->i}m" : "{$diff->i} mins";
            }

            $records[] = [
                'id' => 'C-'.$customer->customer_code,
                'customer' => $customer->company_name ?? $customer->full_name,
                'issue' => 'Follow-up call: '.($customer->notes ?? 'Schedule contact'),
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
                    ->label('Time Left')
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-m-clock')
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
