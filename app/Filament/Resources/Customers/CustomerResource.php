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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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

    protected static ?string $navigationLabel = 'Customer Management';

    protected static string|UnitEnum|null $navigationGroup = 'Customer CRM';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Sales', 'Marketing', 'Support', 'Manager/Analyst']) ?? false;
    }

    /**
     * Follows the official Filament v5 Schema contract.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Main Customer Information')
                ->description('Enter the primary customer identity and profile data.')
                ->schema([
                    TextInput::make('customer_code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->label('Customer Code'),
                    TextInput::make('full_name')
                        ->required()
                        ->maxLength(255)
                        ->label('Full Name'),
                    TextInput::make('job_title')
                        ->maxLength(255)
                        ->label('Job Title'),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->label('Email Address'),
                    TextInput::make('website')
                        ->url()
                        ->maxLength(255)
                        ->label('Website'),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(20)
                        ->label('Phone Number'),
                    TextInput::make('whatsapp')
                        ->tel()
                        ->maxLength(20)
                        ->label('WhatsApp Number'),
                    TextInput::make('company_name')
                        ->maxLength(255)
                        ->label('Company Name'),
                    TextInput::make('industry')
                        ->maxLength(255)
                        ->label('Industry'),
                    TextInput::make('identity_number')
                        ->maxLength(255)
                        ->label('Identity / National ID Number'),
                    TextInput::make('tax_number')
                        ->maxLength(255)
                        ->label('NPWP'),
                    Select::make('gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                        ])
                        ->label('Gender'),
                    DatePicker::make('birth_date')
                        ->label('Birth Date'),
                    Select::make('status')
                        ->options([
                            'Lead'     => 'Lead',
                            'Active'   => 'Active',
                            'Customer' => 'Customer',
                            'Inactive' => 'Inactive',
                        ])
                        ->required()
                        ->default('Lead')
                        ->label('Customer Status'),
                    Select::make('customer_type')
                        ->options([
                            'Individual' => 'Individual',
                            'B2B' => 'B2B',
                            'Retail' => 'Retail',
                            'Enterprise' => 'Enterprise',
                            'Government' => 'Government',
                        ])
                        ->label('Customer Type'),
                    Select::make('source')
                        ->options([
                            'Website' => 'Website',
                            'Referral' => 'Referral',
                            'Campaign' => 'Campaign',
                            'Social Media' => 'Social Media',
                            'Event' => 'Event',
                            'Walk-in' => 'Walk-in',
                            'Other' => 'Other',
                        ])
                        ->label('Data Source'),
                    TextInput::make('lead_score')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->label('Lead Score'),
                    Select::make('preferred_contact_method')
                        ->options([
                            'Email' => 'Email',
                            'Phone' => 'Phone',
                            'WhatsApp' => 'WhatsApp',
                            'Meeting' => 'Meeting',
                        ])
                        ->label('Preferred Contact Method'),
                    DateTimePicker::make('last_contacted_at')
                        ->label('Last Contacted At'),
                    DateTimePicker::make('next_follow_up_at')
                        ->label('Next Follow-up At'),
                    Toggle::make('is_favorite')
                        ->label('Add to Favorite List'),
                    Select::make('assigned_user_id')
                        ->label('Assigned to Sales/Admin')
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
                        ->label('Address')
                        ->columnSpanFull(),
                    TextInput::make('city')
                        ->maxLength(255)
                        ->label('City'),
                    TextInput::make('province')
                        ->maxLength(255)
                        ->label('Province'),
                    TextInput::make('postal_code')
                        ->maxLength(20)
                        ->label('Postal Code'),
                    TextInput::make('country')
                        ->maxLength(255)
                        ->default('Indonesia')
                        ->label('Country'),
                    Textarea::make('notes')
                        ->rows(3)
                        ->maxLength(2000)
                        ->label('Internal Notes')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Customer Segmentation')
                ->description('Group customers with tags such as VIP, B2B, Retail, or High Priority.')
                ->schema([
                    Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Tag Name')
                                ->required()
                                ->maxLength(255)
                                ->unique('tags', 'name'),
                        ])
                        ->label('Tag / Label'),
                ])->columnSpanFull(),

            // FEATURE: Dynamic custom fields
            Section::make('Additional Data Attributes')
                ->description('Admins can add flexible extra fields without changing the database structure.')
                ->collapsible()
                ->schema([
                    Repeater::make('customFields')
                        ->relationship('customFields')
                        ->schema([
                            TextInput::make('field_key')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Example: Tax ID, LinkedIn, Contract Number')
                                ->label('Attribute Name / Field Label'),
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
                                ->helperText('For dropdown fields only. Separate options with commas, for example: Gold, Silver, Bronze.')
                                ->dehydrateStateUsing(fn ($state) => filled($state)
                                    ? collect(explode(',', $state))->map(fn ($option) => trim($option))->filter()->values()->all()
                                    : null)
                                ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                                ->label('Dropdown Options'),
                            TextInput::make('field_value')
                                ->maxLength(500)
                                ->placeholder('Enter the attribute value or dropdown value...')
                                ->label('Text/Dropdown Value'),
                            DatePicker::make('field_date')
                                ->label('Date Value'),
                            Toggle::make('field_boolean')
                                ->label('Checkbox Value'),
                            FileUpload::make('file_path')
                                ->disk('public')
                                ->directory('customer-custom-fields')
                                ->downloadable()
                                ->openable()
                                ->label('File Value'),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->addActionLabel('Add New Flexible Field')
                        ->label('')
                ])->columnSpanFull(),

            Section::make('Customer Attachments')
                ->description('Upload supporting documents such as ID photos, PDF contracts, or legal customer documents.')
                ->collapsible()
                ->schema([
                    Repeater::make('attachments')
                        ->relationship('attachments')
                        ->schema([
                            TextInput::make('file_name')
                                ->required()
                                ->maxLength(255)
                                ->label('Document Name'),
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
                                ->label('File Type'),
                        ])
                        ->columns(3)
                        ->defaultItems(0)
                        ->addActionLabel('Add Attachment')
                        ->label(''),
                ])->columnSpanFull(),

            // FEATURE: Customer activity history and timeline
            Section::make('Activity History & Timeline')
                ->description('Automatic system log that records this customer data change history.')
                ->collapsible()
                ->compact()
                ->schema([
                    Repeater::make('activityLogs')
                        ->relationship('activityLogs')
                        ->schema([
                            TextInput::make('created_at')
                                ->label('Event Time')
                                ->disabled(),
                            TextInput::make('description')
                                ->label('History Detail')
                                ->disabled()
                                ->columnSpan(4),
                        ])
                        ->columns(5)
                        ->disabled()
                        ->dehydrated(false)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->label('')
                ])->columnSpanFull()
        ]);
    }

    /**
     * Follows the official Filament v5 Table contract.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_code')->searchable()->sortable()->label('Code'),
                IconColumn::make('is_favorite')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->label('Favorite'),
                TextColumn::make('full_name')->searchable()->sortable()->label('Full Name'),
                TextColumn::make('email')->searchable()->label('Email'),
                TextColumn::make('company_name')->searchable()->label('Company'),
                TextColumn::make('industry')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Industry'),
                TextColumn::make('city')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('City'),
                TextColumn::make('assignedUser.name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Unassigned')
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
                    ->label('Created'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Lead'     => 'Lead',
                        'Active'   => 'Active',
                        'Customer' => 'Customer',
                        'Inactive' => 'Inactive',
                    ])
                    ->label('Status Filter'),
                SelectFilter::make('company_name')
                    ->options(fn (): array => Customer::query()
                        ->whereNotNull('company_name')
                        ->where('company_name', '!=', '')
                        ->distinct()
                        ->orderBy('company_name')
                        ->pluck('company_name', 'company_name')
                        ->all())
                    ->searchable()
                    ->label('Company Filter'),
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
                    ->trueLabel('Favorites Only')
                    ->falseLabel('Not Favorite')
                    ->native(false),
            ])
            ->recordActions([
                Action::make('toggleFavorite')
                    ->label(fn (Customer $record): string => $record->is_favorite ? 'Remove Favorite' : 'Add Favorite')
                    ->icon(fn (Customer $record): string => $record->is_favorite ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color('warning')
                    ->action(function (Customer $record): void {
                        $record->update(['is_favorite' => ! $record->is_favorite]);

                        Notification::make()
                            ->title($record->is_favorite ? 'Customer added to favorite list' : 'Customer removed from favorite list')
                            ->success()
                            ->send();
                    }),
                ViewAction::make()
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Support', 'Manager/Analyst']) ?? false),
                EditAction::make()
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'Sales', 'Marketing']) ?? false),
                Action::make('mergeDuplicate')
                    ->label('Merge Duplicate')
                    ->icon('heroicon-o-arrows-right-left')
                    ->color('warning')
                    ->schema(fn (Customer $record): array => [
                        Select::make('duplicate_id')
                            ->label('Duplicate customer to merge')
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
                            ->label('Merge strategy')
                            ->options([
                                'prefer_complete' => 'Use the most complete data',
                                'prefer_primary' => 'Keep the primary customer data',
                            ])
                            ->default('prefer_complete')
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Merge Duplicate Customer')
                    ->modalDescription('Tags, attachments, and custom fields from the duplicate customer will be moved to the primary customer. The duplicate customer will then be deleted.')
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
                            ->title('Duplicate customer merged successfully')
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
