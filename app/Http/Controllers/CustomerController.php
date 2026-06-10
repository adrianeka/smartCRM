<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer; // Mengambil data dari Model Customer
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;



class CustomerController extends Controller
{
    // 1. READ: Menampilkan semua data pelanggan

#[OA\Get(
    path: "/api/v1/customers",
    summary: "Get All Customers",
    tags: ["Customer"],
    security: [["sanctum" => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: "List of customers"
        )
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

            $q->where(
                'name',
                'like',
                "%{$request->tag}%"
            );

        });

    }

    if ($request->filled('favorite')) {

    $query->where(
        'is_favorite',
        $request->favorite
        );

    }


    if ($request->filled('sort')) {

        $query->orderBy(
            $request->sort,
            'asc'
        );
    }

    $customers = $query->paginate(
        $request->get('per_page', 10)
    );
            return response()->json([
                'status' => 'success',
                'data' => $customers
            ]);
}

    #[OA\Post(
    path: "/api/v1/customers",
    summary: "Create Customer",
    tags: ["Customer"],
    security: [["sanctum" => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string", example: "John Doe"),
                new OA\Property(property: "email", type: "string", example: "john@example.com"),
                new OA\Property(property: "phone", type: "string", example: "08123456789")
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: "Customer created")
    ]
)]

    // 2. CREATE: Menyimpan data pelanggan baru ke database
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

    return response()->json([
        'status' => 'success',
        'message' => 'Pelanggan baru berhasil ditambahkan!',
        'data' => $customer
    ]);
    }


    #[OA\Get(
    path: "/api/v1/customers/{id}",
    summary: "Get Customer Detail",
    tags: ["Customer"],
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
responses: [
    new OA\Response(
        response: 200,
        description: "List of customers",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "string", example: "success"),
                new OA\Property(
                    property: "data",
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Customer")
                )
            ]
        )
    )
]
)]


    // 3. READ: Menampilkan detail satu pelanggan spesifik
    public function show($id)
    {
        $customer = Customer::with('tags')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $customer
        ]);
    }

    #[OA\Put(
    path: "/api/v1/customers/{id}",
    summary: "Update Customer",
    tags: ["Customer"],
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Customer updated"
        )
    ]
)]


    // 4. UPDATE: Mengubah data pelanggan yang sudah ada
    public function update(Request $request, $id)
    {

        $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email',
        'phone' => 'nullable|string|max:255',
        'custom_fields' => 'nullable|array'
    ]);
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data pelanggan berhasil diperbarui!',
            'data' => $customer
        ]);
    }


    public function activities($id)
{
    $customer = Customer::findOrFail($id);

    $activities = $customer->activityLogs()
        ->latest()
        ->paginate(20);

    return response()->json([
        'status' => 'success',
        'data' => $activities
    ]);
}

    #[OA\Delete(
    path: "/api/v1/customers/{id}",
    summary: "Delete Customer",
    tags: ["Customer"],
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Customer deleted"
        )
    ]
)]

    // 5. DELETE: Menghapus data pelanggan
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data pelanggan berhasil dihapus!'
        ]);
    }

    public function exportJson()
{
    return response()->json([
        'status' => 'success',
        'data' => Customer::all()
    ]);
}

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
            'status'
        ]);

        foreach ($customers as $customer) {

            fputcsv($file, [
                $customer->customer_code,
                $customer->full_name,
                $customer->email,
                $customer->phone,
                $customer->company_name,
                $customer->status
            ]);
        }

        fclose($file);
    };

    return response()->stream(
        $callback,
        200,
        $headers
    );
}

public function importCsv(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt,xlsx'
    ]);

    Excel::import(
        new CustomerImport,
        $request->file('file')
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Customer berhasil diimport'
    ]);
}

public function duplicates()
{
    return response()->json([
        'status' => 'success'
    ]);
}


public function attachTags(Request $request, $id)
{
    $customer = Customer::findOrFail($id);

    $request->validate([
        'tag_ids' => 'required|array'
    ]);

    $customer->tags()->syncWithoutDetaching(
        $request->tag_ids
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Tag berhasil ditambahkan',
        'data' => $customer->tags
    ]);
}


public function toggleFavorite($id)
{
    $customer = Customer::findOrFail($id);

    $customer->is_favorite =
        !$customer->is_favorite;

    $customer->save();

    return response()->json([
        'status' => 'success',
        'data' => $customer
    ]);
}

}
