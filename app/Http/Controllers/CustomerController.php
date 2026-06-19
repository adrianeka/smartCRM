<?php

namespace App\Http\Controllers;

use App\Imports\CustomerImport;
use App\Models\Customer;
use App\Models\CustomerAttachment;
use App\Models\Notification;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Attributes as OA;

class CustomerController extends Controller
{
    #[OA\Get(
        path: '/api/v1/customers',
        summary: 'Get All Customers',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of customers'
            ),
        ]
    )]
    public function index(Request $request)
    {
        $query = Customer::with(['tags', 'customFields']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_code', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%");

                if (DB::connection()->getDriverName() === 'mysql') {
                    $q->orWhereFullText([
                        'customer_code',
                        'full_name',
                        'email',
                        'phone',
                        'whatsapp',
                        'company_name',
                        'industry',
                        'city',
                        'province',
                        'source',
                        'notes',
                    ], $search);
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('company_name')) {
            $query->where('company_name', 'like', "%{$request->company_name}%");
        }

        if ($request->filled('industry')) {
            $query->where('industry', 'like', "%{$request->industry}%");
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->tag}%");
            });
        }

        if ($request->filled('custom_field_key')) {
            $query->whereHas('customFields', function ($q) use ($request) {
                $q->where('field_key', $request->custom_field_key);

                if ($request->filled('custom_field_value')) {
                    $q->where('field_value', 'like', "%{$request->custom_field_value}%");
                }
            });
        }

        if ($request->filled('favorite')) {
            $query->where('is_favorite', $request->boolean('favorite'));
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        if ($request->filled('sort')) {
            $sort = in_array($request->sort, [
                'customer_code',
                'full_name',
                'email',
                'phone',
                'whatsapp',
                'company_name',
                'industry',
                'status',
                'customer_type',
                'source',
                'lead_score',
                'assigned_user_id',
                'created_at',
                'updated_at',
            ], true) ? $request->sort : 'created_at';

            $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $customers = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'status' => 'success',
            'data' => $customers,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/customers',
        summary: 'Create Customer',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '08123456789'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Customer created'),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate($this->customerValidationRules());

        $customer = DB::transaction(function () use ($validated) {
            $customer = Customer::create($this->customerPayload($validated));
            $this->syncCustomFields($customer, $validated['custom_fields'] ?? null);

            return $customer->load(['tags', 'customFields']);
        });

        Notification::create([
            'title' => 'Customer Created',
            'message' => 'Customer '.$customer->full_name.' has been registered',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pelanggan baru berhasil ditambahkan!',
            'data' => $customer,
        ]);
    }

    #[OA\Get(
        path: '/api/v1/customers/{id}',
        summary: 'Get Customer Detail',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of customers',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Customer')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function show($id)
    {
        $customer = Customer::with(['tags', 'customFields', 'attachments'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    #[OA\Put(
        path: '/api/v1/customers/{id}',
        summary: 'Update Customer',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Customer updated'
            ),
        ]
    )]
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate($this->customerValidationRules($customer->id, true));

        $customer = DB::transaction(function () use ($customer, $validated) {
            $customer->update($this->customerPayload($validated));
            $this->syncCustomFields($customer, $validated['custom_fields'] ?? null);

            return $customer->fresh(['tags', 'customFields']);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data pelanggan berhasil diperbarui!',
            'data' => $customer,
        ]);
    }

    public function activities($id)
    {
        $customer = Customer::findOrFail($id);
        $activities = $customer->activityLogs()->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $activities,
        ]);
    }

    #[OA\Delete(
        path: '/api/v1/customers/{id}',
        summary: 'Delete Customer',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Customer deleted'
            ),
        ]
    )]
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data pelanggan berhasil dihapus!',
        ]);
    }

    public function exportJson()
    {
        return response()->json([
            'status' => 'success',
            'data' => Customer::with(['tags', 'customFields'])->get(),
        ]);
    }

    public function exportCsv()
    {
        $customers = Customer::with('assignedUser')->get();
        $filename = 'customers.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'customer_code',
                'full_name',
                'email',
                'phone',
                'whatsapp',
                'website',
                'company_name',
                'industry',
                'job_title',
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
                'is_favorite',
                'assigned_to',
            ]);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->customer_code,
                    $customer->full_name,
                    $customer->email,
                    $customer->phone,
                    $customer->whatsapp,
                    $customer->website,
                    $customer->company_name,
                    $customer->industry,
                    $customer->job_title,
                    $customer->identity_number,
                    $customer->tax_number,
                    $customer->gender,
                    optional($customer->birth_date)->format('Y-m-d'),
                    $customer->address,
                    $customer->city,
                    $customer->province,
                    $customer->postal_code,
                    $customer->country,
                    $customer->status,
                    $customer->customer_type,
                    $customer->source,
                    $customer->lead_score,
                    $customer->preferred_contact_method,
                    optional($customer->last_contacted_at)->format('Y-m-d H:i:s'),
                    optional($customer->next_follow_up_at)->format('Y-m-d H:i:s'),
                    $customer->notes,
                    $customer->is_favorite ? 'yes' : 'no',
                    $customer->assignedUser?->name,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);

        Excel::import(new CustomerImport, $request->file('file'));

        return response()->json([
            'status' => 'success',
            'message' => 'Customer berhasil diimport',
        ]);
    }

    public function exportExcel()
    {
        $export = new class implements FromCollection, WithHeadings, WithMapping
        {
            public function collection()
            {
                return Customer::with(['assignedUser', 'customFields'])->orderBy('full_name')->get();
            }

            public function headings(): array
            {
                return [
                    'customer_code',
                    'full_name',
                    'email',
                    'phone',
                    'whatsapp',
                    'website',
                    'company_name',
                    'industry',
                    'job_title',
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
                    'is_favorite',
                    'assigned_to',
                    'custom_fields',
                ];
            }

            public function map($customer): array
            {
                return [
                    $customer->customer_code,
                    $customer->full_name,
                    $customer->email,
                    $customer->phone,
                    $customer->whatsapp,
                    $customer->website,
                    $customer->company_name,
                    $customer->industry,
                    $customer->job_title,
                    $customer->identity_number,
                    $customer->tax_number,
                    $customer->gender,
                    optional($customer->birth_date)->format('Y-m-d'),
                    $customer->address,
                    $customer->city,
                    $customer->province,
                    $customer->postal_code,
                    $customer->country,
                    $customer->status,
                    $customer->customer_type,
                    $customer->source,
                    $customer->lead_score,
                    $customer->preferred_contact_method,
                    optional($customer->last_contacted_at)->format('Y-m-d H:i:s'),
                    optional($customer->next_follow_up_at)->format('Y-m-d H:i:s'),
                    $customer->notes,
                    $customer->is_favorite ? 'yes' : 'no',
                    $customer->assignedUser?->name,
                    $customer->customFields
                        ->mapWithKeys(fn ($field) => [$field->field_key => $this->customFieldDisplayValue($field)])
                        ->toJson(),
                ];
            }

            private function customFieldDisplayValue($field): mixed
            {
                return match ($field->field_type) {
                    'date' => optional($field->field_date)->format('Y-m-d'),
                    'checkbox' => $field->field_boolean,
                    'file' => $field->file_path,
                    default => $field->field_value,
                };
            }
        };

        return Excel::download($export, 'customers.xlsx');
    }

    public function duplicates(Request $request)
    {
        $threshold = (int) $request->get('threshold', 50);
        $customers = Customer::with('customFields')->orderBy('id')->get();
        $candidates = [];

        for ($i = 0; $i < $customers->count(); $i++) {
            for ($j = $i + 1; $j < $customers->count(); $j++) {
                $score = $this->duplicateScore($customers[$i], $customers[$j]);

                if ($score >= $threshold) {
                    $candidates[] = [
                        'score' => $score,
                        'match_reasons' => $this->duplicateReasons($customers[$i], $customers[$j]),
                        'primary_candidate' => $customers[$i],
                        'duplicate_candidate' => $customers[$j],
                    ];
                }
            }
        }

        usort($candidates, fn ($a, $b) => $b['score'] <=> $a['score']);

        return response()->json([
            'status' => 'success',
            'data' => $candidates,
        ]);
    }

    public function merge(Request $request, $id)
    {
        $validated = $request->validate([
            'duplicate_id' => [
                'required',
                'integer',
                'exists:customers,id',
                Rule::notIn([(int) $id]),
            ],
            'strategy' => 'nullable|in:prefer_primary,prefer_complete,override',
            'field_values' => 'nullable|array',
            'custom_fields' => 'nullable|array',
        ]);

        $primary = DB::transaction(function () use ($id, $validated, $request) {
            $primary = Customer::with(['customFields', 'tags', 'attachments'])->findOrFail($id);
            $duplicate = Customer::with(['customFields', 'tags', 'attachments'])->findOrFail($validated['duplicate_id']);
            $strategy = $validated['strategy'] ?? 'prefer_complete';

            $primary->update($this->mergedCustomerPayload(
                $primary,
                $duplicate,
                $strategy,
                $validated['field_values'] ?? []
            ));

            $this->mergeCustomFields($primary, $duplicate, $validated['custom_fields'] ?? []);
            $primary->tags()->syncWithoutDetaching($duplicate->tags->pluck('id')->all());
            $duplicate->attachments()->update(['customer_id' => $primary->id]);

            $duplicate->delete();

            $activity = activity('customer-merge')->performedOn($primary);

            if ($request->user()) {
                $activity->causedBy($request->user());
            }

            $activity
                ->withProperties([
                    'merged_customer_id' => $validated['duplicate_id'],
                    'strategy' => $strategy,
                ])
                ->log('Customer duplicate merged');

            return $primary->fresh(['tags', 'customFields', 'attachments']);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data pelanggan duplikat berhasil digabungkan',
            'data' => $primary,
        ]);
    }

    public function attachTags(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->validate([
            'tag_ids' => 'required|array',
        ]);

        $customer->tags()->syncWithoutDetaching($request->tag_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Tag berhasil ditambahkan',
            'data' => $customer->tags,
        ]);
    }

    public function toggleFavorite($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->is_favorite = ! $customer->is_favorite;
        $customer->save();

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    public function attachments($id)
    {
        $customer = Customer::with('attachments')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $customer->attachments,
        ]);
    }

    public function uploadAttachment(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('customer-attachments', 'public');

        $attachment = CustomerAttachment::create([
            'customer_id' => $customer->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil diupload',
            'data' => $attachment,
        ]);
    }

    public function downloadAttachment($attachmentId)
    {
        $attachment = CustomerAttachment::findOrFail($attachmentId);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download(
            $attachment->file_path,
            $attachment->file_name
        );
    }

    public function previewAttachment($attachmentId)
    {
        $attachment = CustomerAttachment::findOrFail($attachmentId);

        return response()->json([
            'status' => 'success',
            'data' => [
                'file_name' => $attachment->file_name,
                'file_type' => $attachment->file_type,
                'url' => asset('storage/'.$attachment->file_path),
            ],
        ]);
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = CustomerAttachment::findOrFail($attachmentId);

        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Attachment berhasil dihapus',
        ]);
    }

    private function customerValidationRules(?int $customerId = null, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return [
            'customer_code' => [
                $required,
                'string',
                'max:255',
                Rule::unique('customers', 'customer_code')->ignore($customerId),
            ],
            'full_name' => [$required, 'string', 'max:255'],
            'email' => [
                $required,
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customerId),
            ],
            'phone' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'company_name' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'identity_number' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'customer_type' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'lead_score' => 'nullable|integer|min:0|max:100',
            'preferred_contact_method' => 'nullable|string|max:255',
            'last_contacted_at' => 'nullable|date',
            'next_follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string',
            'is_favorite' => 'nullable|boolean',
            'assigned_user_id' => 'nullable|exists:users,id',
            'custom_fields' => 'nullable|array',
        ];
    }

    private function customerPayload(array $validated): array
    {
        return collect($validated)
            ->only([
                'customer_code',
                'full_name',
                'job_title',
                'email',
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
                'is_favorite',
                'assigned_user_id',
                'custom_fields',
            ])
            ->all();
    }

    private function syncCustomFields(Customer $customer, ?array $fields): void
    {
        if ($fields === null) {
            return;
        }

        $normalized = $this->normalizeCustomFields($fields);

        $customer->customFields()->delete();
        $customer->customFields()->createMany($normalized);

        $customer->forceFill([
            'custom_fields' => collect($normalized)->mapWithKeys(
                fn ($field) => [$field['field_key'] => $field['field_value']]
            )->all(),
        ])->saveQuietly();
    }

    private function normalizeCustomFields(array $fields): array
    {
        $isList = array_is_list($fields);

        return collect($fields)
            ->map(function ($value, $key) use ($isList) {
                if ($isList) {
                    return [
                        'field_key' => $value['field_key'] ?? null,
                        'field_type' => $value['field_type'] ?? 'text',
                        'field_options' => $value['field_options'] ?? null,
                        'field_value' => $value['field_value'] ?? null,
                        'field_date' => $value['field_date'] ?? null,
                        'field_boolean' => $value['field_boolean'] ?? null,
                        'file_path' => $value['file_path'] ?? null,
                    ];
                }

                return [
                    'field_key' => $key,
                    'field_type' => 'text',
                    'field_options' => null,
                    'field_value' => $value,
                    'field_date' => null,
                    'field_boolean' => null,
                    'file_path' => null,
                ];
            })
            ->filter(fn ($field) => filled($field['field_key']))
            ->map(fn ($field) => [
                'field_key' => (string) $field['field_key'],
                'field_type' => (string) ($field['field_type'] ?? 'text'),
                'field_options' => $field['field_options'] ?? null,
                'field_value' => (string) ($field['field_value'] ?? ''),
                'field_date' => $field['field_date'] ?? null,
                'field_boolean' => $field['field_boolean'] ?? null,
                'file_path' => $field['file_path'] ?? null,
            ])
            ->values()
            ->all();
    }

    private function duplicateScore(Customer $first, Customer $second): int
    {
        $score = 0;

        if ($this->sameFilled($first->email, $second->email)) {
            $score += 50;
        }

        if ($this->sameFilled($this->normalizePhone($first->phone), $this->normalizePhone($second->phone))) {
            $score += 30;
        }

        if ($this->sameFilled($this->normalizeText($first->full_name), $this->normalizeText($second->full_name))) {
            $score += 20;
        }

        if ($this->sameFilled($this->normalizeText($first->company_name), $this->normalizeText($second->company_name))) {
            $score += 10;
        }

        return min($score, 100);
    }

    private function duplicateReasons(Customer $first, Customer $second): array
    {
        return collect([
            'email' => $this->sameFilled($first->email, $second->email),
            'phone' => $this->sameFilled($this->normalizePhone($first->phone), $this->normalizePhone($second->phone)),
            'full_name' => $this->sameFilled($this->normalizeText($first->full_name), $this->normalizeText($second->full_name)),
            'company_name' => $this->sameFilled($this->normalizeText($first->company_name), $this->normalizeText($second->company_name)),
        ])->filter()->keys()->values()->all();
    }

    private function sameFilled(?string $first, ?string $second): bool
    {
        return filled($first) && filled($second) && mb_strtolower($first) === mb_strtolower($second);
    }

    private function normalizePhone(?string $phone): ?string
    {
        return $phone ? preg_replace('/\D+/', '', $phone) : null;
    }

    private function normalizeText(?string $value): ?string
    {
        return $value ? trim(preg_replace('/\s+/', ' ', mb_strtolower($value))) : null;
    }

    private function mergedCustomerPayload(Customer $primary, Customer $duplicate, string $strategy, array $overrides): array
    {
        $fields = [
            'customer_code',
            'full_name',
            'job_title',
            'email',
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

        foreach ($fields as $field) {
            $payload[$field] = match ($strategy) {
                'override' => $overrides[$field] ?? $primary->{$field},
                'prefer_primary' => $primary->{$field},
                default => filled($primary->{$field}) ? $primary->{$field} : $duplicate->{$field},
            };
        }

        return $payload;
    }

    private function mergeCustomFields(Customer $primary, Customer $duplicate, array $overrides): void
    {
        $fields = $primary->customFields
            ->mapWithKeys(fn ($field) => [$field->field_key => $field->field_value])
            ->all();

        foreach ($duplicate->customFields as $field) {
            $fields[$field->field_key] ??= $field->field_value;
        }

        foreach ($overrides as $key => $value) {
            $fields[$key] = $value;
        }

        $this->syncCustomFields($primary, $fields);
    }
}
