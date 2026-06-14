<?php

namespace App\Filament\Resources\AnalyticsReports;

use App\Filament\Resources\AnalyticsReports\Pages\CreateAnalyticsReport;
use App\Filament\Resources\AnalyticsReports\Pages\EditAnalyticsReport;
use App\Filament\Resources\AnalyticsReports\Pages\ListAnalyticsReports;
use App\Models\AnalyticsReportDefinition;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Services\Analytics\ReportBuilderService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AnalyticsReportResource extends Resource
{
    protected static ?string $model = AnalyticsReportDefinition::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Custom Reports';

    protected static string|UnitEnum|null $navigationGroup = 'Analytics & Reporting';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('View:Analytics') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konfigurasi Laporan')
                ->description('Pilih dan drag kolom untuk mengatur urutan laporan.')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Laporan')
                        ->required()
                        ->maxLength(255),
                    Select::make('visibility')
                        ->label('Visibilitas')
                        ->options([
                            'private' => 'Private',
                            'team' => 'Tim Analytics',
                        ])
                        ->default('private')
                        ->required(),
                    Repeater::make('columns')
                        ->label('Kolom Laporan')
                        ->simple(
                            Select::make('column')
                                ->options(ReportBuilderService::columnOptions())
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->required(),
                        )
                        ->default(array_keys(ReportBuilderService::columnOptions()))
                        ->minItems(1)
                        ->reorderable()
                        ->addActionLabel('Tambah Kolom')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Filter Laporan')
                ->description('Filter ini akan dipakai pada export manual dan laporan terjadwal.')
                ->schema([
                    DatePicker::make('filters.from')
                        ->label('Dari Tanggal'),
                    DatePicker::make('filters.until')
                        ->label('Sampai Tanggal')
                        ->afterOrEqual('filters.from'),
                    Select::make('filters.owner_id')
                        ->label('Sales Person')
                        ->options(fn (): array => User::role('Sales')->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    Select::make('filters.product_id')
                        ->label('Produk')
                        ->options(fn (): array => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    Select::make('filters.region')
                        ->label('Wilayah')
                        ->options(fn (): array => Customer::query()
                            ->whereNotNull('province')
                            ->distinct()
                            ->orderBy('province')
                            ->pluck('province', 'province')
                            ->all())
                        ->searchable(),
                ])
                ->columns(3)
                ->collapsible(),

            Section::make('Branding Export')
                ->schema([
                    TextInput::make('branding.title')
                        ->label('Nama Brand')
                        ->default('SmartCRM Analytics')
                        ->maxLength(100),
                    TextInput::make('branding.subtitle')
                        ->label('Subjudul')
                        ->default('Laporan performa bisnis')
                        ->maxLength(255),
                    ColorPicker::make('branding.primary_color')
                        ->label('Warna Utama')
                        ->default('#f59e0b'),
                ])
                ->columns(3)
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Laporan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label('Pemilik')
                    ->sortable(),
                TextColumn::make('visibility')
                    ->label('Visibilitas')
                    ->badge(),
                TextColumn::make('schedules_count')
                    ->label('Schedule')
                    ->counts('schedules'),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    Action::make('downloadCsv')
                        ->label('Export CSV')
                        ->icon('heroicon-o-document-text')
                        ->url(fn (AnalyticsReportDefinition $record): string => route('analytics.reports.export', [
                            'report' => $record,
                            'format' => 'csv',
                        ]))
                        ->openUrlInNewTab(),
                    Action::make('downloadExcel')
                        ->label('Export Excel')
                        ->icon('heroicon-o-table-cells')
                        ->color('success')
                        ->url(fn (AnalyticsReportDefinition $record): string => route('analytics.reports.export', [
                            'report' => $record,
                            'format' => 'xlsx',
                        ]))
                        ->openUrlInNewTab(),
                    Action::make('downloadPdf')
                        ->label('Export PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->url(fn (AnalyticsReportDefinition $record): string => route('analytics.reports.export', [
                            'report' => $record,
                            'format' => 'pdf',
                        ]))
                        ->openUrlInNewTab(),
                ])->label('Export'),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user?->hasAnyRole(['super_admin', 'Manager/Analyst'])) {
            return $query;
        }

        return $query->where(
            fn (Builder $builder): Builder => $builder
                ->where('owner_id', $user?->id)
                ->orWhere('visibility', 'team'),
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnalyticsReports::route('/'),
            'create' => CreateAnalyticsReport::route('/create'),
            'edit' => EditAnalyticsReport::route('/{record}/edit'),
        ];
    }
}
