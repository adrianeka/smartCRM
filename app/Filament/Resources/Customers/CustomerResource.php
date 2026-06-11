<?php

namespace App\Filament\Resources\Customers;

use App\Models\Customer;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    // Trik union-type andalan lu biar lolos dari error Intelephense P1077
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Customer Management';

    protected static string|UnitEnum|null $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form->schema([
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
                            'Inactive' => 'Inactive (Tidak Aktif)',
                        ])
                        ->required()
                        ->default('Lead')
                        ->label('Status Pelanggan'),
                ])->columns(2),

            // FITUR 2: KOLOM DATA FLEKSIBEL (DYNAMIC CUSTOM FIELDS)
            Section::make('Atribut Data Tambahan (Custom Fields)')
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

            // FITUR 1: CATATAN RIWAYAT & TIMELINE (CUSTOMER ACTIVITY LOGS)
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
                            TextInput::make('activity_type')
                                ->label('Aksi')
                                ->disabled(),
                            TextInput::make('causer')
                                ->label('Oleh Admin')
                                ->disabled(),
                            TextInput::make('description')
                                ->label('Detail Catatan Riwayat')
                                ->disabled()
                                ->columnSpan(2),
                        ])
                        ->columns(5)
                        ->addable(false)   // Dikunci biar gak bisa ditambah manual
                        ->deletable(false) // Dikunci biar gak bisa dihapus manual
                        ->reorderable(false)
                        ->label('')
                ])->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_code')->searchable()->sortable()->label('Kode'),
                TextColumn::make('full_name')->searchable()->sortable()->label('Nama Lengkap'),
                TextColumn::make('email')->searchable()->label('Email'),
                TextColumn::make('company_name')->searchable()->label('Perusahaan'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lead'     => 'warning',
                        'Active'   => 'success',
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
                        'Inactive' => 'Inactive',
                    ])
                    ->label('Filter Status'),
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
            'edit'   => EditCustomer::route('/{record}/edit'),
        ];
    }
}
