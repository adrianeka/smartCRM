<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopDealsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 6;

    protected static ?string $heading = 'Deal Teratas';

    public function table(Table $table): Table
    {
        $deals = Customer::query()
            ->whereIn('status', ['Lead', 'Qualified', 'Proposal', 'Negotiation'])
            ->whereNotNull('lead_score')
            ->orderBy('lead_score', 'desc')
            ->limit(5)
            ->get();

        if ($deals->isEmpty()) {
            return $table
                ->records(fn (): array => [
                    ['id' => 1, 'title' => 'Transformasi Digital', 'company' => 'Innovate Solutions', 'value' => 'Probabilitas 45%'],
                    ['id' => 2, 'title' => 'Langganan Tahunan SaaS', 'company' => 'CloudTech Inc.', 'value' => 'Probabilitas 80%'],
                    ['id' => 3, 'title' => 'Lisensi Perangkat Lunak Perusahaan', 'company' => 'TechCorp Industries', 'value' => 'Probabilitas 75%'],
                    ['id' => 4, 'title' => 'Layanan Konsultasi', 'company' => 'Enterprise Dynamics', 'value' => 'Probabilitas 20%'],
                    ['id' => 5, 'title' => 'Proyek Migrasi Cloud', 'company' => 'Global Systems Ltd', 'value' => 'Probabilitas 60%'],
                ])
                ->columns([
                    TextColumn::make('title')
                        ->label('Deal / Pelanggan')
                        ->description(fn ($record) => $record['company']),
                    TextColumn::make('value')
                        ->label('Skor Lead')
                        ->alignEnd(),
                ])
                ->paginated(false);
        }

        return $table
            ->query(
                fn () => Customer::query()
                    ->whereIn('status', ['Lead', 'Qualified', 'Proposal', 'Negotiation'])
                    ->whereNotNull('lead_score')
                    ->orderBy('lead_score', 'desc')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('full_name')
                    ->label('Deal / Pelanggan')
                    ->description(fn ($record) => $record->company_name ?? 'Pribadi'),
                TextColumn::make('lead_score')
                    ->label('Skor Lead')
                    ->formatStateUsing(fn ($state) => $state.'% probabilitas')
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
