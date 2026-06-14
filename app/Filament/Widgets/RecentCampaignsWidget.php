<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentCampaignsWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Kampanye Terbaru';

    public function table(Table $table): Table
    {
        $hasData = Customer::count() > 0;

        if ($hasData) {
            $campaignLeads = Customer::where('source', 'Campaign')->count();
            $referralLeads = Customer::where('source', 'Referral')->count();
            $websiteLeads = Customer::where('source', 'Website')->count();
            $socialLeads = Customer::where('source', 'Social Media')->count();
            $eventLeads = Customer::where('source', 'Event')->count();
            $otherLeads = Customer::where('source', 'Other')->count();

            return $table
                ->records(fn (): array => [
                    ['id' => 1, 'name' => 'Summer Promo 2026', 'status' => 'Running', 'leads' => $campaignLeads],
                    ['id' => 2, 'name' => 'Webinar Follow-up', 'status' => 'Scheduled', 'leads' => $referralLeads],
                    ['id' => 3, 'name' => 'Q1 Newsletter', 'status' => 'Completed', 'leads' => $websiteLeads],
                    ['id' => 4, 'name' => 'Product X Launch', 'status' => 'Draft', 'leads' => $otherLeads],
                    ['id' => 5, 'name' => 'B2B Client Outreach', 'status' => 'Running', 'leads' => $socialLeads],
                    ['id' => 6, 'name' => 'Holiday Special', 'status' => 'Scheduled', 'leads' => $eventLeads],
                ])
                ->columns([
                    TextColumn::make('name')
                        ->weight('bold'),
                    TextColumn::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'Running' => 'success',
                            'Scheduled' => 'warning',
                            'Completed' => 'info',
                            'Draft' => 'gray',
                            default => 'gray',
                        }),
                    TextColumn::make('leads')
                        ->label('Lead')
                        ->numeric()
                        ->alignEnd(),
                ])
                ->paginated(false);
        }

        return $table
            ->records(fn (): array => [
                ['id' => 1, 'name' => 'Summer Promo 2026', 'status' => 'Running', 'leads' => 340],
                ['id' => 2, 'name' => 'Webinar Follow-up', 'status' => 'Scheduled', 'leads' => 0],
                ['id' => 3, 'name' => 'Q1 Newsletter', 'status' => 'Completed', 'leads' => 1205],
                ['id' => 4, 'name' => 'Product X Launch', 'status' => 'Draft', 'leads' => 0],
                ['id' => 5, 'name' => 'B2B Client Outreach', 'status' => 'Running', 'leads' => 45],
                ['id' => 6, 'name' => 'Holiday Special', 'status' => 'Scheduled', 'leads' => 0],
            ])
            ->columns([
                TextColumn::make('name')
                    ->weight('bold'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Running' => 'success',
                        'Scheduled' => 'warning',
                        'Completed' => 'info',
                        'Draft' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('leads')
                    ->label('Leads')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
