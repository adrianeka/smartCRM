<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class TopDealsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 3;
    protected static ?int $sort = 6;
    protected static ?string $heading = 'Top Deals by Value';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => [
                ['id' => 1, 'title' => 'Digital Transformation', 'company' => 'Innovate Solutions', 'value' => '$215,000', 'probability' => '45% prob'],
                ['id' => 2, 'title' => 'SaaS Annual Subscription', 'company' => 'CloudTech Inc.', 'value' => '$178,000', 'probability' => '80% prob'],
                ['id' => 3, 'title' => 'Enterprise Software License', 'company' => 'TechCorp Industries', 'value' => '$125,000', 'probability' => '75% prob'],
                ['id' => 4, 'title' => 'Consulting Services', 'company' => 'Enterprise Dynamics', 'value' => '$95,000', 'probability' => '20% prob'],
                ['id' => 5, 'title' => 'Cloud Migration Project', 'company' => 'Global Systems Ltd', 'value' => '$89,500', 'probability' => '60% prob'],
            ])
            ->columns([
                TextColumn::make('title')
                    ->label('Deal')
                    ->description(fn ($record) => is_array($record) ? $record['company'] : ''),
                TextColumn::make('value')
                    ->label('Value')
                    ->description(fn ($record) => is_array($record) ? $record['probability'] : '')
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
