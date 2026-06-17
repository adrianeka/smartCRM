<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{


    #[OA\Get(
        path: "/api/v1/opportunities",
        summary: "List Opportunities",
        tags: ["Opportunities"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Opportunity list"
            )
        ]
    )]
    public function index()
    {
        $opportunities = Opportunity::with('customer')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $opportunities
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

    public function update(Request $request, $id)
    {
        $opportunity = Opportunity::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'stage' => 'sometimes|string',
            'deal_value' => 'sometimes|numeric',
            'probability' => 'sometimes|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $opportunity->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $opportunity
        ]);
    }


    #[OA\Delete(
    path: "/api/v1/opportunities/{id}",
    summary: "Delete Opportunity",
    tags: ["Opportunities"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Opportunity deleted"
        )
    ]
)]

    public function destroy($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        $opportunity->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Opportunity deleted successfully'
        ]);
    }




    #[OA\Patch(
    path: "/api/v1/opportunities/{id}/stage",
    summary: "Update Opportunity Stage",
    tags: ["Opportunities"],
    responses: [
            new OA\Response(
                response: 200,
                description: "Stage updated"
            )
        ]
    )]

    public function updateStage(Request $request, $id)
    {
        $validated = $request->validate([
            'stage' => 'required|in:Lead,Prospect,Negotiation,Proposal,Won,Lost'
        ]);

        $opportunity = Opportunity::findOrFail($id);

        $opportunity->update([
            'stage' => $validated['stage']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Stage updated successfully',
            'data' => $opportunity
        ]);
    }


        public function pipeline()
        {
            $stages = [
                'Lead',
                'Prospect',
                'Negotiation',
                'Proposal',
                'Won',
                'Lost'
            ];

            $result = [];

            foreach ($stages as $stage) {
                $result[$stage] = Opportunity::where('stage', $stage)
                    ->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        }
}
