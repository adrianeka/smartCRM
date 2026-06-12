<?php

namespace App\Filament\Resources\Customers;

use App\Models\Customer;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;
use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\DuplicateCustomers;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Manajemen Pelanggan';

    protected static string|UnitEnum|null $navigationGroup = 'CRM Pelanggan';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Sales', 'Marketing', 'Support', 'Manager/Analyst']) ?? false;
    }

    /**
     * Memenuhi kontrak parameter Schema resmi Filament v5
     */
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Utama Pelanggan')
                ->description('Masukkan data profil utama identitas pelanggan.')
                ->schema([
                    TextInput::make('customer_code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->label('Kode Pelanggan'),
                    TextInput::make('full_name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nama Lengkap'),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->label('Alamat Email'),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(20)
                        ->label('No. Telepon'),
                    TextInput::make('company_name')
                        ->maxLength(255)
                        ->label('Nama Perusahaan'),
                    Select::make('status')
                        ->options([
                            'Lead'     => 'Lead (Calon)',
                            'Active'   => 'Active (Aktif)',
                            'Customer' => 'Customer (Pelanggan)',
                            'Inactive' => 'Inactive (Tidak Aktif)',
                        ])
                        ->required()
                        ->default('Lead')
                        ->label('Status Pelanggan'),
                    Select::make('assigned_user_id')
                        ->label('Ditugaskan ke Sales/Admin')
                        ->options(fn (): array => User::query()
                            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['super_admin', 'Sales']))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all())
                        ->searchable()
                        ->preload(),
                ])->columns(2),

            Section::make('Segmentasi Pelanggan')
                ->description('Kelompokkan pelanggan dengan tag seperti VIP, B2B, Retail, atau Prioritas Tinggi.')
                ->schema([
                    Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Nama Tag')
                                ->required()
                                ->maxLength(255)
                                ->unique('tags', 'name'),
                        ])
                        ->label('Tag / Label'),
                ])->columnSpanFull(),

            // FITUR: KOLOM DATA FLEKSIBEL (DYNAMIC CUSTOM FIELDS)
            Section::make('Atribut Data Tambahan')
                ->description('Admin bisa menambahkan kolom inputan baru secara fleksibel tanpa ubah struktur codingan.')
                ->collapsible()
                ->schema([
                    Repeater::make('customFields')
                        ->relationship('customFields')
                        ->schema([
                            TextInput::make('field_key')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: NPWP, LinkedIn, No. Akta')
                                ->label('Nama Atribut / Label Kolom'),
                            TextInput::make('field_value')
                                ->maxLength(500)
                                ->placeholder('Masukkan isi data atribut...')
                                ->label('Isi Data / Nilai'),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->addActionLabel('Tambah Kolom Fleksibel Baru')
                        ->label('')
                ])->columnSpanFull(),

            // FITUR: CATATAN RIWAYAT & TIMELINE (CUSTOMER ACTIVITY LOGS)
            Section::make('Riwayat Aktivitas & Linimasa Kronologis')
                ->description('Log sistem otomatis yang mencatat riwayat perubahan data pelanggan ini.')
                ->collapsible()
                ->compact()
                ->schema([
                    Repeater::make('activityLogs')
                        ->relationship('activityLogs')
                        ->schema([
                            TextInput::make('created_at')
                                ->label('Waktu Kejadian')
                                ->disabled(),
                            TextInput::make('description')
                                ->label('Detail Catatan Riwayat')
                                ->disabled()
                                ->columnSpan(4),
                        ])
                        ->columns(5)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->label('')
                ])->columnSpanFull()
        ]);
    }

    /**
     * Memenuhi kontrak parameter Table resmi Filament v5
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_code')->searchable()->sortable()->label('Kode'),
                TextColumn::make('full_name')->searchable()->sortable()->label('Nama Lengkap'),
                TextColumn::make('email')->searchable()->label('Email'),
                TextColumn::make('company_name')->searchable()->label('Perusahaan'),
                TextColumn::make('assignedUser.name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum ditugaskan')
                    ->label('PIC Sales/Admin'),
                TextColumn::make('tags.name')
                    ->badge()
                    ->separator(',')
                        ->label('Tag'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lead'     => 'warning',
                        'Active'   => 'success',
                        'Customer' => 'success',
                        'Inactive' => 'danger',
                        default    => 'gray',
                    })
                    ->label('Status'),
                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Lead'     => 'Lead',
                        'Active'   => 'Active',
                        'Customer' => 'Customer',
                        'Inactive' => 'Inactive',
                    ])
                    ->label('Filter Status'),
                SelectFilter::make('company_name')
                    ->options(fn (): array => Customer::query()
                        ->whereNotNull('company_name')
                        ->where('company_name', '!=', '')
                        ->distinct()
                        ->orderBy('company_name')
                        ->pluck('company_name', 'company_name')
                        ->all())
                    ->searchable()
                    ->label('Filter Perusahaan'),
                SelectFilter::make('assigned_user_id')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter PIC'),
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Filter Tag'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Support', 'Manager/Analyst']) ?? false),
                EditAction::make()
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Sales', 'Marketing']) ?? false),
                Action::make('mergeDuplicate')
                    ->label('Merge Duplikat')
                    ->icon('heroicon-o-arrows-right-left')
                    ->color('warning')
                    ->schema(fn (Customer $record): array => [
                        Select::make('duplicate_id')
                            ->label('Customer duplikat yang akan digabung')
                            ->options(
                                Customer::query()
                                    ->whereKeyNot($record->id)
                                    ->orderBy('full_name')
                                    ->get()
                                    ->mapWithKeys(fn (Customer $customer) => [
                                        $customer->id => "{$customer->customer_code} - {$customer->full_name} ({$customer->email})",
                                    ])
                                    ->all()
                            )
                            ->searchable()
                            ->required(),
                        Select::make('strategy')
                            ->label('Strategi merge')
                            ->options([
                                'prefer_complete' => 'Pakai data yang paling lengkap',
                                'prefer_primary' => 'Pertahankan data customer utama',
                            ])
                            ->default('prefer_complete')
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Gabungkan Customer Duplikat')
                    ->modalDescription('Data tag, lampiran, dan custom fields dari customer duplikat akan dipindahkan ke customer utama. Customer duplikat kemudian dihapus.')
                    ->action(function (Customer $record, array $data): void {
                        DB::transaction(function () use ($record, $data): void {
                            $primary = $record->fresh(['customFields', 'tags', 'attachments']);
                            $duplicate = Customer::with(['customFields', 'tags', 'attachments'])->findOrFail($data['duplicate_id']);
                            $strategy = $data['strategy'] ?? 'prefer_complete';

                            if ($primary->is($duplicate)) {
                                return;
                            }

                            if ($strategy === 'prefer_complete') {
                                $primary->update([
                                    'phone' => $primary->phone ?: $duplicate->phone,
                                    'company_name' => $primary->company_name ?: $duplicate->company_name,
                                    'status' => $primary->status ?: $duplicate->status,
                                ]);
                            }

                            $customFields = $primary->customFields
                                ->mapWithKeys(fn ($field) => [$field->field_key => $field->field_value])
                                ->all();

                            foreach ($duplicate->customFields as $field) {
                                $customFields[$field->field_key] ??= $field->field_value;
                            }

                            $primary->customFields()->delete();
                            $primary->customFields()->createMany(
                                collect($customFields)
                                    ->map(fn ($value, $key) => [
                                        'field_key' => $key,
                                        'field_value' => $value,
                                    ])
                                    ->values()
                                    ->all()
                            );

                            $primary->forceFill(['custom_fields' => $customFields])->saveQuietly();
                            $primary->tags()->syncWithoutDetaching($duplicate->tags->pluck('id')->all());
                            $duplicate->attachments()->update(['customer_id' => $primary->id]);

                            activity('customer-merge')
                                ->performedOn($primary)
                                ->causedBy(auth()->user())
                                ->withProperties([
                                    'merged_customer_id' => $duplicate->id,
                                    'strategy' => $strategy,
                                ])
                                ->log('Customer duplicate merged from admin panel');

                            $duplicate->delete();
                        });

                        Notification::make()
                            ->title('Customer duplikat berhasil digabungkan')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Sales']) ?? false),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'duplicates' => DuplicateCustomers::route('/duplicates'),
            'view'   => ViewCustomer::route('/{record}'),
            'edit'   => EditCustomer::route('/{record}/edit'),
        ];
    }
}
