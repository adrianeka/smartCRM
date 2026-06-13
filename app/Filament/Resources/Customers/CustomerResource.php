<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\DuplicateCustomers;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Models\Customer;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use UnitEnum;

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
                    TextInput::make('job_title')
                        ->maxLength(255)
                        ->label('Jabatan'),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->label('Alamat Email'),
                    TextInput::make('website')
                        ->url()
                        ->maxLength(255)
                        ->label('Website'),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(20)
                        ->label('No. Telepon'),
                    TextInput::make('whatsapp')
                        ->tel()
                        ->maxLength(20)
                        ->label('No. WhatsApp'),
                    TextInput::make('company_name')
                        ->maxLength(255)
                        ->label('Nama Perusahaan'),
                    TextInput::make('industry')
                        ->maxLength(255)
                        ->label('Industri'),
                    TextInput::make('identity_number')
                        ->maxLength(255)
                        ->label('No. Identitas / KTP'),
                    TextInput::make('tax_number')
                        ->maxLength(255)
                        ->label('NPWP'),
                    Select::make('gender')
                        ->options([
                            'male' => 'Laki-laki',
                            'female' => 'Perempuan',
                            'other' => 'Lainnya',
                        ])
                        ->label('Jenis Kelamin'),
                    DatePicker::make('birth_date')
                        ->label('Tanggal Lahir'),
                    Select::make('status')
                        ->options([
                            'Lead' => 'Lead (Calon)',
                            'Active' => 'Active (Aktif)',
                            'Customer' => 'Customer (Pelanggan)',
                            'Inactive' => 'Inactive (Tidak Aktif)',
                        ])
                        ->required()
                        ->default('Lead')
                        ->label('Status Pelanggan'),
                    Select::make('customer_type')
                        ->options([
                            'Individual' => 'Individual',
                            'B2B' => 'B2B',
                            'Retail' => 'Retail',
                            'Enterprise' => 'Enterprise',
                            'Government' => 'Government',
                        ])
                        ->label('Tipe Customer'),
                    Select::make('source')
                        ->options([
                            'Website' => 'Website',
                            'Referral' => 'Referral',
                            'Campaign' => 'Campaign',
                            'Social Media' => 'Social Media',
                            'Event' => 'Event',
                            'Walk-in' => 'Walk-in',
                            'Other' => 'Lainnya',
                        ])
                        ->label('Sumber Data'),
                    TextInput::make('lead_score')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->label('Lead Score'),
                    Select::make('preferred_contact_method')
                        ->options([
                            'Email' => 'Email',
                            'Phone' => 'Telepon',
                            'WhatsApp' => 'WhatsApp',
                            'Meeting' => 'Meeting',
                        ])
                        ->label('Metode Kontak Favorit'),
                    DateTimePicker::make('last_contacted_at')
                        ->label('Terakhir Dihubungi'),
                    DateTimePicker::make('next_follow_up_at')
                        ->label('Jadwal Follow-up Berikutnya'),
                    Toggle::make('is_favorite')
                        ->label('Masuk Favorite List'),
                    Select::make('assigned_user_id')
                        ->label('Ditugaskan ke Sales/Admin')
                        ->options(fn (): array => User::query()
                            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['super_admin', 'Sales']))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all())
                        ->searchable()
                        ->preload(),
                    Textarea::make('address')
                        ->rows(3)
                        ->maxLength(1000)
                        ->label('Alamat')
                        ->columnSpanFull(),
                    TextInput::make('city')
                        ->maxLength(255)
                        ->label('Kota'),
                    TextInput::make('province')
                        ->maxLength(255)
                        ->label('Provinsi'),
                    TextInput::make('postal_code')
                        ->maxLength(20)
                        ->label('Kode Pos'),
                    TextInput::make('country')
                        ->maxLength(255)
                        ->default('Indonesia')
                        ->label('Negara'),
                    Textarea::make('notes')
                        ->rows(3)
                        ->maxLength(2000)
                        ->label('Catatan Internal')
                        ->columnSpanFull(),
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
                            Select::make('field_type')
                                ->options([
                                    'text' => 'Text',
                                    'dropdown' => 'Dropdown',
                                    'date' => 'Date',
                                    'checkbox' => 'Checkbox',
                                    'file' => 'File',
                                ])
                                ->default('text')
                                ->required()
                                ->label('Tipe Field'),
                            TextInput::make('field_options')
                                ->helperText('Khusus dropdown. Pisahkan opsi dengan koma, contoh: Gold, Silver, Bronze.')
                                ->dehydrateStateUsing(fn ($state) => filled($state)
                                    ? collect(explode(',', $state))->map(fn ($option) => trim($option))->filter()->values()->all()
                                    : null)
                                ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                                ->label('Opsi Dropdown'),
                            TextInput::make('field_value')
                                ->maxLength(500)
                                ->placeholder('Masukkan isi data atribut atau nilai dropdown...')
                                ->label('Nilai Text/Dropdown'),
                            DatePicker::make('field_date')
                                ->label('Nilai Tanggal'),
                            Toggle::make('field_boolean')
                                ->label('Nilai Checkbox'),
                            FileUpload::make('file_path')
                                ->disk('public')
                                ->directory('customer-custom-fields')
                                ->downloadable()
                                ->openable()
                                ->label('Nilai File'),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->addActionLabel('Tambah Kolom Fleksibel Baru')
                        ->label(''),
                ])->columnSpanFull(),

            Section::make('Lampiran Pelanggan')
                ->description('Upload dokumen pendukung seperti foto KTP, kontrak PDF, atau dokumen legal pelanggan.')
                ->collapsible()
                ->schema([
                    Repeater::make('attachments')
                        ->relationship('attachments')
                        ->schema([
                            TextInput::make('file_name')
                                ->required()
                                ->maxLength(255)
                                ->label('Nama Dokumen'),
                            FileUpload::make('file_path')
                                ->required()
                                ->disk('public')
                                ->directory('customer-attachments')
                                ->downloadable()
                                ->openable()
                                ->previewable()
                                ->label('File'),
                            TextInput::make('file_type')
                                ->maxLength(255)
                                ->placeholder('application/pdf, image/jpeg')
                                ->label('Tipe File'),
                        ])
                        ->columns(3)
                        ->defaultItems(0)
                        ->addActionLabel('Tambah Lampiran')
                        ->label(''),
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
                        ->label(''),
                ])->columnSpanFull(),
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
                IconColumn::make('is_favorite')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->label('Favorit'),
                TextColumn::make('full_name')->searchable()->sortable()->label('Nama Lengkap'),
                TextColumn::make('email')->searchable()->label('Email'),
                TextColumn::make('company_name')->searchable()->label('Perusahaan'),
                TextColumn::make('industry')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Industri'),
                TextColumn::make('city')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Kota'),
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
                        'Lead' => 'warning',
                        'Active' => 'success',
                        'Customer' => 'success',
                        'Inactive' => 'danger',
                        default => 'gray',
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
                        'Lead' => 'Lead',
                        'Active' => 'Active',
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
                TernaryFilter::make('is_favorite')
                    ->label('Favorite List')
                    ->trueLabel('Hanya Favorit')
                    ->falseLabel('Bukan Favorit')
                    ->native(false),
            ])
            ->recordActions([
                Action::make('toggleFavorite')
                    ->label(fn (Customer $record): string => $record->is_favorite ? 'Hapus Favorit' : 'Jadikan Favorit')
                    ->icon(fn (Customer $record): string => $record->is_favorite ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color('warning')
                    ->action(function (Customer $record): void {
                        $record->update(['is_favorite' => ! $record->is_favorite]);

                        Notification::make()
                            ->title($record->is_favorite ? 'Customer masuk favorite list' : 'Customer dihapus dari favorite list')
                            ->success()
                            ->send();
                    }),
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
                                $mergeFields = [
                                    'job_title',
                                    'website',
                                    'phone',
                                    'whatsapp',
                                    'company_name',
                                    'industry',
                                    'identity_number',
                                    'tax_number',
                                    'gender',
                                    'birth_date',
                                    'address',
                                    'city',
                                    'province',
                                    'postal_code',
                                    'country',
                                    'status',
                                    'customer_type',
                                    'source',
                                    'lead_score',
                                    'preferred_contact_method',
                                    'last_contacted_at',
                                    'next_follow_up_at',
                                    'notes',
                                    'assigned_user_id',
                                    'is_favorite',
                                ];

                                $payload = [];

                                foreach ($mergeFields as $field) {
                                    $payload[$field] = filled($primary->{$field}) ? $primary->{$field} : $duplicate->{$field};
                                }

                                $primary->update($payload);
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'duplicates' => DuplicateCustomers::route('/duplicates'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
