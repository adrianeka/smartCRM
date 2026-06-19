<?php

namespace App\Filament\Resources\AnalyticsReportSchedules;

use App\Filament\Resources\AnalyticsReportSchedules\Pages\CreateAnalyticsReportSchedule;
use App\Filament\Resources\AnalyticsReportSchedules\Pages\EditAnalyticsReportSchedule;
use App\Filament\Resources\AnalyticsReportSchedules\Pages\ListAnalyticsReportSchedules;
use App\Models\AnalyticsReportDefinition;
use App\Models\AnalyticsReportSchedule;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AnalyticsReportScheduleResource extends Resource
{
    protected static ?string $model = AnalyticsReportSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Scheduled Reports';

    protected static string|UnitEnum|null $navigationGroup = 'Analytics & Reporting';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('View:Analytics') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Jadwal Laporan')
                ->schema([
                    Select::make('report_definition_id')
                        ->label('Custom Report')
                        ->options(fn (): array => AnalyticsReportDefinition::query()
                            ->when(
                                ! auth()->user()?->hasAnyRole(['super_admin', 'Manager/Analyst']),
                                fn (Builder $query): Builder => $query
                                    ->where('owner_id', auth()->id())
                                    ->orWhere('visibility', 'team'),
                            )
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all())
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('frequency')
                        ->label('Frekuensi')
                        ->options([
                            'weekly' => 'Mingguan',
                            'monthly' => 'Bulanan',
                        ])
                        ->required(),
                    Select::make('format')
                        ->label('Format')
                        ->options([
                            'csv' => 'CSV',
                            'xlsx' => 'Excel',
                            'pdf' => 'PDF',
                        ])
                        ->default('xlsx')
                        ->required(),
                    DateTimePicker::make('next_run_at')
                        ->label('Pengiriman Berikutnya')
                        ->default(now()->addWeek())
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                    Repeater::make('recipients')
                        ->label('Penerima Email')
                        ->simple(
                            TextInput::make('email')
                                ->email()
                                ->required(),
                        )
                        ->minItems(1)
                        ->reorderable(false)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reportDefinition.name')
                    ->label('Laporan')
                    ->searchable(),
                TextColumn::make('frequency')
                    ->label('Frekuensi')
                    ->badge(),
                TextColumn::make('format')
                    ->label('Format')
                    ->badge(),
                TextColumn::make('next_run_at')
                    ->label('Pengiriman Berikutnya')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_run_at')
                    ->label('Terakhir Dikirim')
                    ->since()
                    ->placeholder('Belum pernah'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('next_run_at')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        return $user?->hasAnyRole(['super_admin', 'Manager/Analyst'])
            ? $query
            : $query->where('created_by', $user?->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnalyticsReportSchedules::route('/'),
            'create' => CreateAnalyticsReportSchedule::route('/create'),
            'edit' => EditAnalyticsReportSchedule::route('/{record}/edit'),
        ];
    }
}
