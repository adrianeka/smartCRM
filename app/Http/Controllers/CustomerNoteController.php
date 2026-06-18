<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerNote;

class CustomerNoteController extends Controller
{
public function index($id)
{
    $customer = Customer::findOrFail($id);

    return response()->json([
        'status' => 'success',
        'data' => $customer->notes()->with('user')->get()
    ]);
}

public function store(Request $request, $id)
{
    $customer = Customer::findOrFail($id);

    $note = CustomerNote::create([
        'customer_id' => $customer->id,
        'user_id' => 1,
        'note' => $request->note
    ]);

    return response()->json([
        'status' => 'success',
        'data' => $note
    ]);
}
}
