<?php

namespace App\Http\Controllers;

use App\Imports\CustomerImport;
use App\Models\Notification;
use App\Models\Customer;
use App\Models\CustomerAttachment;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $query = Customer::with('tags');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->tag}%");
            });
        }

        if ($request->filled('favorite')) {
            $query->where('is_favorite', $request->favorite);
        }

        if ($request->filled('sort')) {
            $query->orderBy($request->sort, 'asc');
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
        $request->validate([
            'customer_code' => 'required|string|unique:customers|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers|max:255',
            'phone' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        $customer = Customer::create($request->all());
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
        $customer = Customer::with('tags')->findOrFail($id);

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
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email',
            'phone' => 'nullable|string|max:255',
            'custom_fields' => 'nullable|array',
        ]);
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data pelanggan berhasil diperbarui!',
            'data' => $customer,
        ]);
    }

     #[OA\Get(
        path: '/api/v1/customers/{id}/activities',
        summary: 'Get Customer Activity Logs',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Customer ID'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Customer activity logs',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Customer not found'),
        ]
    )]
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


        #[OA\Get(
        path: '/api/v1/customers/export/json',
        summary: 'Export Customers as JSON',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'All customers in JSON format',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
        ]
    )]
    public function exportJson()
    {
        return response()->json([
            'status' => 'success',
            'data' => Customer::all(),
        ]);
    }



        #[OA\Get(
        path: '/api/v1/customers/export/csv',
        summary: 'Export Customers as CSV',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'CSV file download',
                content: new OA\MediaType(mediaType: 'text/csv')
            ),
        ]
    )]
    public function exportCsv()
    {
        $customers = Customer::all();
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
                'company_name',
                'status',
            ]);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->customer_code,
                    $customer->full_name,
                    $customer->email,
                    $customer->phone,
                    $customer->company_name,
                    $customer->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }





        #[OA\Post(
        path: '/api/v1/customers/import/csv',
        summary: 'Import Customers from CSV/Excel',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'file', type: 'string', format: 'binary', description: 'CSV or Excel file'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Import successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Customer berhasil diimport'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx',
        ]);

        Excel::import(new CustomerImport, $request->file('file'));

        return response()->json([
            'status' => 'success',
            'message' => 'Customer berhasil diimport',
        ]);
    }


        #[OA\Get(
        path: '/api/v1/customers/duplicates',
        summary: 'Get Duplicate Customers',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Duplicate customers list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                    ]
                )
            ),
        ]
    )]
    public function duplicates()
    {
        return response()->json([
            'status' => 'success',
        ]);
    }




     #[OA\Post(
        path: '/api/v1/customers/{id}/tags',
        summary: 'Attach Tags to Customer',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Customer ID'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['tag_ids'],
                properties: [
                    new OA\Property(property: 'tag_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tags attached successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Tag berhasil ditambahkan'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Customer not found'),
        ]
    )]

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


        #[OA\Post(
        path: '/api/v1/customers/{id}/favorite',
        summary: 'Toggle Customer Favorite',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Customer ID'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Favorite status toggled',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Customer not found'),
        ]
    )]
    public function toggleFavorite($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->is_favorite = !$customer->is_favorite;
        $customer->save();

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }


        #[OA\Get(
        path: '/api/v1/customers/{id}/attachments',
        summary: 'Get Customer Attachments',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Customer ID'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of attachments',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Customer not found'),
        ]
    )]

    public function attachments($id)
    {
        $customer = Customer::with('attachments')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $customer->attachments,
        ]);
    }




      #[OA\Post(
        path: '/api/v1/customers/{id}/attachments',
        summary: 'Upload Attachment for Customer',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Customer ID'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'file', type: 'string', format: 'binary', description: 'File to upload (max 10MB)'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'File uploaded successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'File berhasil diupload'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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



        #[OA\Get(
        path: '/api/v1/attachments/{attachmentId}/download',
        summary: 'Download Customer Attachment',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'attachmentId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Attachment ID'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'File download'),
            new OA\Response(response: 404, description: 'Attachment not found'),
        ]
    )]

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





        #[OA\Get(
        path: '/api/v1/attachments/{attachmentId}/preview',
        summary: 'Preview Customer Attachment',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'attachmentId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Attachment ID'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Attachment preview info',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'file_name', type: 'string', example: 'document.pdf'),
                                new OA\Property(property: 'file_type', type: 'string', example: 'application/pdf'),
                                new OA\Property(property: 'url', type: 'string', example: 'http://example.com/storage/...'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Attachment not found'),
        ]
    )]
    public function previewAttachment($attachmentId)
    {
        $attachment = CustomerAttachment::findOrFail($attachmentId);

        return response()->json([
            'status' => 'success',
            'data' => [
                'file_name' => $attachment->file_name,
                'file_type' => $attachment->file_type,
                'url' => asset('storage/' . $attachment->file_path),
            ],
        ]);
    }


     #[OA\Delete(
        path: '/api/v1/attachments/{attachmentId}',
        summary: 'Delete Customer Attachment',
        tags: ['Customer'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'attachmentId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Attachment ID'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Attachment deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Attachment berhasil dihapus'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Attachment not found'),
        ]
    )]

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
}
