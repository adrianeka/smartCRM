<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AnalyticsReports\AnalyticsReportResource;
use App\Filament\Resources\AnalyticsReportSchedules\AnalyticsReportScheduleResource;
use App\Filament\Widgets\Analytics\AnalyticsKpiOverview;
use App\Filament\Widgets\Analytics\RevenueByProductChart;
use App\Filament\Widgets\Analytics\RevenueByRegionChart;
use App\Filament\Widgets\Analytics\RevenueTrendChart;
use App\Filament\Widgets\Analytics\SalesFunnelChart;
use App\Filament\Widgets\Analytics\SalesPerformanceWidget;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use UnitEnum;

class AnalyticsDashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static string $routePath = 'analytics';

    protected static ?string $title = 'Analytics & Reporting';

    protected static ?string $navigationLabel = 'Analytics';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string|UnitEnum|null $navigationGroup = 'Analytics & Reporting';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('View:Analytics') ?? false;
    }

    public function getSubheading(): ?string
    {
        return 'Pantau performa pendapatan, funnel penjualan, pelanggan, produk, dan wilayah.';
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 6,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $schema->components([
            DatePicker::make('from')
                ->label('Dari Tanggal')
                ->default(now()->startOfYear()),
            DatePicker::make('until')
                ->label('Sampai Tanggal')
                ->default(now()),
            Select::make('owner_id')
                ->label('Sales Person')
                ->options(fn (): array => $user?->hasRole('Sales')
                    ? [$user->id => $user->name]
                    : User::role('Sales')->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->preload(),
            Select::make('product_id')
                ->label('Produk')
                ->options(fn (): array => Product::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all())
                ->searchable()
                ->preload(),
            Select::make('region')
                ->label('Wilayah')
                ->options(fn (): array => Customer::query()
                    ->whereNotNull('province')
                    ->distinct()
                    ->orderBy('province')
                    ->pluck('province', 'province')
                    ->all())
                ->searchable(),
        ]);
    }

    public function getWidgets(): array
    {
        return [
            AnalyticsKpiOverview::class,
            RevenueTrendChart::class,
            SalesFunnelChart::class,
            RevenueByProductChart::class,
            RevenueByRegionChart::class,
            SalesPerformanceWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('customReports')
                ->label('Custom Reports')
                ->icon('heroicon-o-document-chart-bar')
                ->url(AnalyticsReportResource::getUrl()),
            Action::make('scheduledReports')
                ->label('Scheduled Reports')
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->url(AnalyticsReportScheduleResource::getUrl()),
        ];
    }
}
