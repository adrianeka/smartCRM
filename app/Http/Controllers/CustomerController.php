<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request; // Mengambil data dari Model Customer

class CustomerController extends Controller
{
    // 1. READ: Menampilkan semua data pelanggan
    public function index()
    {
        $customers = Customer::all();

        // Kita return pakai JSON dulu biar gampang dites
        return response()->json([
            'status' => 'success',
            'data' => $customers,
        ]);
    }

    // 2. CREATE: Menyimpan data pelanggan baru ke database
    public function store(Request $request)
    {
        // Validasi data yang masuk
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
            'data' => $customer,
        ]);
    }

    // 3. READ: Menampilkan detail satu pelanggan spesifik
    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    // 4. UPDATE: Mengubah data pelanggan yang sudah ada
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data pelanggan berhasil diperbarui!',
            'data' => $customer,
        ]);
    }

    // 5. DELETE: Menghapus data pelanggan
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data pelanggan berhasil dihapus!',
        ]);
    }
}
