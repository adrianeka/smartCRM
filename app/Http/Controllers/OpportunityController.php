<?php

namespace App\Http\Controllers;
use App\Models\Opportunity;

use Illuminate\Http\Request;

class OpportunityController extends Controller
{

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => Opportunity::with('customer')->paginate(10)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'deal_value' => 'nullable|numeric',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $opportunity = Opportunity::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $opportunity
        ], 201);
    }


    public function show(string $id)
    {
        $opportunity = Opportunity::with('customer')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $opportunity
        ]);
    }

    public function update(Request $request, string $id)
    {
        $opportunity = Opportunity::findOrFail($id);

        $opportunity->update($request->only([
            'title',
            'stage',
            'deal_value',
            'probability',
            'expected_close_date',
            'notes',
        ]));

        return response()->json([
            'status' => 'success',
            'data' => $opportunity
        ]);
    }

    public function destroy(string $id)
    {
        Opportunity::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Opportunity deleted'
        ]);
    }
}
