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
                    ['id' => 1, 'title' => 'Digital Transformation', 'company' => 'Innovate Solutions', 'value' => '45% probability'],
                    ['id' => 2, 'title' => 'SaaS Annual Subscription', 'company' => 'CloudTech Inc.', 'value' => '80% probability'],
                    ['id' => 3, 'title' => 'Enterprise Software License', 'company' => 'TechCorp Industries', 'value' => '75% probability'],
                    ['id' => 4, 'title' => 'Consulting Services', 'company' => 'Enterprise Dynamics', 'value' => '20% probability'],
                    ['id' => 5, 'title' => 'Cloud Migration Project', 'company' => 'Global Systems Ltd', 'value' => '60% probability'],
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
