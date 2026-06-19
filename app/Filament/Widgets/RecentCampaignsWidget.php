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
                    ['id' => 1, 'name' => 'Promo Musim Panas 2026', 'status' => 'Berjalan', 'leads' => $campaignLeads],
                    ['id' => 2, 'name' => 'Follow-up Webinar', 'status' => 'Dijadwalkan', 'leads' => $referralLeads],
                    ['id' => 3, 'name' => 'Nawala Q1', 'status' => 'Selesai', 'leads' => $websiteLeads],
                    ['id' => 4, 'name' => 'Peluncuran Produk X', 'status' => 'Draf', 'leads' => $otherLeads],
                    ['id' => 5, 'name' => 'Penjangkauan Klien B2B', 'status' => 'Berjalan', 'leads' => $socialLeads],
                    ['id' => 6, 'name' => 'Spesial Liburan', 'status' => 'Dijadwalkan', 'leads' => $eventLeads],
                ])
                ->columns([
                    TextColumn::make('name')
                        ->weight('bold'),
                    TextColumn::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'Berjalan' => 'success',
                            'Dijadwalkan' => 'warning',
                            'Selesai' => 'info',
                            'Draf' => 'gray',
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
                ['id' => 1, 'name' => 'Promo Musim Panas 2026', 'status' => 'Berjalan', 'leads' => 340],
                ['id' => 2, 'name' => 'Follow-up Webinar', 'status' => 'Dijadwalkan', 'leads' => 0],
                ['id' => 3, 'name' => 'Nawala Q1', 'status' => 'Selesai', 'leads' => 1205],
                ['id' => 4, 'name' => 'Peluncuran Produk X', 'status' => 'Draf', 'leads' => 0],
                ['id' => 5, 'name' => 'Penjangkauan Klien B2B', 'status' => 'Berjalan', 'leads' => 45],
                ['id' => 6, 'name' => 'Spesial Liburan', 'status' => 'Dijadwalkan', 'leads' => 0],
            ])
            ->columns([
                TextColumn::make('name')
                    ->weight('bold'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Berjalan' => 'success',
                        'Dijadwalkan' => 'warning',
                        'Selesai' => 'info',
                        'Draf' => 'gray',
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
