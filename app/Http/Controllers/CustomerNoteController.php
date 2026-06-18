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

    $notes = $customer->notes()
        ->whereNull('parent_id')
        ->with(['user','replies.user'])
        ->latest()
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $notes
    ]);
}

public function store(Request $request, $id)
{
    $customer = Customer::findOrFail($id);

    $request->validate([
    'note' => 'required|string',
    'parent_id' => 'nullable|exists:customer_notes,id'
]);

$note = CustomerNote::create([
    'customer_id' => $customer->id,
    'user_id' => 1,
    'parent_id' => $request->parent_id,
    'note' => $request->note
]);

    return response()->json([
        'status' => 'success',
        'data' => $note
    ]);
}
}
