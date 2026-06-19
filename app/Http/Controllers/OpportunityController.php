<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class OpportunityController extends Controller
{
    #[OA\Get(
        path: '/api/v1/opportunities',
        summary: 'List Opportunities',
        tags: ['Opportunities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Opportunity list'
            ),
        ]
    )]
    public function index()
    {
        $opportunities = Opportunity::with('customer')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $opportunities,
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
            'data' => $opportunity,
        ], 201);
    }

    public function show(string $id)
    {
        $opportunity = Opportunity::with('customer')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $opportunity,
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
            'notes' => 'nullable|string',
            'result_reason' => 'nullable|string|max:255',
        ]);

        $opportunity->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $opportunity,
        ]);
    }

    #[OA\Delete(
        path: '/api/v1/opportunities/{id}',
        summary: 'Delete Opportunity',
        tags: ['Opportunities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Opportunity deleted'
            ),
        ]
    )]
    public function destroy($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        $opportunity->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Opportunity deleted successfully',
        ]);
    }

    #[OA\Patch(
        path: '/api/v1/opportunities/{id}/stage',
        summary: 'Update Opportunity Stage',
        tags: ['Opportunities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Stage updated'
            ),
        ]
    )]
    public function updateStage(Request $request, $id)
    {
        $validated = $request->validate([
            'stage' => 'required|in:Lead,Prospect,Negotiation,Proposal,Won,Lost',
        ]);

        $opportunity = Opportunity::findOrFail($id);

        $opportunity->update([
            'stage' => $validated['stage'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Stage updated successfully',
            'data' => $opportunity,
        ]);
    }

    #[OA\Get(
        path: '/api/v1/opportunities/pipeline',
        summary: 'Opportunity Pipeline',
        tags: ['Opportunities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pipeline data'
            ),
        ]
    )]
    public function pipeline()
    {
        $stages = [
            'Lead',
            'Prospect',
            'Negotiation',
            'Proposal',
            'Won',
            'Lost',
        ];

        $result = [];

        foreach ($stages as $stage) {
            $stageField = 'stage';
            $result[$stage] = Opportunity::query()->where($stageField, $stage)
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    #[OA\Get(
        path: '/api/v1/opportunities/{id}/score',
        summary: 'Lead Scoring',
        tags: ['Opportunities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lead score calculated'
            ),
        ]
    )]
    public function score($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        $score = 0;

        if ($opportunity->probability >= 80) {
            $score += 50;
        } elseif ($opportunity->probability >= 50) {
            $score += 30;
        }

        if ($opportunity->deal_value >= 10000000) {
            $score += 50;
        } elseif ($opportunity->deal_value >= 5000000) {
            $score += 30;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'opportunity_id' => $opportunity->id,
                'title' => $opportunity->title,
                'score' => $score,
            ],
        ]);
    }

    #[OA\Get(
        path: '/api/v1/opportunities/forecast',
        summary: 'Forecast Dashboard',
        tags: ['Opportunities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Forecast statistics'
            ),
        ]
    )]
    public function forecast()
    {
        $totalPipelineValue = Opportunity::sum('deal_value');

        $expectedRevenue = Opportunity::get()
            ->sum(function ($opportunity) {
                return ($opportunity->deal_value * $opportunity->probability) / 100;
            });

        $stageField = 'stage';
        $wonDeals = Opportunity::query()->where($stageField, 'Won')->count();

        $lostDeals = Opportunity::query()->where($stageField, 'Lost')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_pipeline_value' => $totalPipelineValue,
                'expected_revenue' => round($expectedRevenue, 2),
                'won_deals' => $wonDeals,
                'lost_deals' => $lostDeals,
            ],
        ]);
    }

    #[OA\Get(
        path: '/api/v1/opportunities/win-loss',
        summary: 'Win Loss Analysis',
        tags: ['Opportunities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Win loss statistics'
            ),
        ]
    )]
    public function winLossAnalysis()
    {
        $stageField = 'stage';
        $won = Opportunity::query()->where($stageField, 'Won')->count();

        $lost = Opportunity::query()->where($stageField, 'Lost')->count();

        $reasons = Opportunity::query()->whereNotNull('result_reason')
            ->selectRaw('result_reason, COUNT(*) as total')
            ->groupBy('result_reason')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'won_deals' => $won,
                'lost_deals' => $lost,
                'reasons' => $reasons,
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/v1/opportunities/{id}/send-proposal',
        summary: 'Send Proposal Simulation',
        tags: ['Opportunities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Proposal sent'
            ),
        ]
    )]
    public function sendProposal($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Proposal sent successfully',
            'data' => [
                'opportunity_id' => $opportunity->id,
                'customer_id' => $opportunity->customer_id,
                'proposal_title' => $opportunity->title,
                'deal_value' => $opportunity->deal_value,
                'sent_at' => now(),
            ],
        ]);
    }

    public function calendar()
    {
        $data = Opportunity::query()->whereNotNull('expected_close_date')
            ->select(
                'id',
                'title',
                'expected_close_date',
                'stage'
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    #[OA\Post(
        path: "/api/v1/opportunities/{id}/send-whatsapp",
        summary: "Send WhatsApp Simulation",
        tags: ["Opportunities"],
        responses: [
            new OA\Response(
                response: 200,
                description: "WhatsApp simulated"
            )
        ]
    )]
    public function sendWhatsapp($id)
    {
        $opportunity = Opportunity::with('customer')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'WhatsApp message simulated successfully',
            'data' => [
                'opportunity_id' => $opportunity->id,
                'customer_id' => $opportunity->customer_id,
                'phone' => $opportunity->customer->phone,
                'message' => 'Follow up for opportunity: '.$opportunity->title,
                'sent_at' => now()
            ]
        ]);
    }
}
