<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\Opportunity;
use App\Models\OpportunityTask;
use OpenApi\Attributes as OA;

class ActivityFeedController extends Controller
{


    #[OA\Get(
    path: "/api/v1/customers/{id}/activity-feed",
    summary: "Customer Activity Feed",
    tags: ["Collaboration"],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "Customer ID"
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Customer activity timeline"
        )
    ]
)]

    public function index($id)
    {
        $customer = Customer::findOrFail($id);

        $activities = collect();

        /*
        Notes
        */
        $notes = CustomerNote::where('customer_id', $customer->id)
            ->get()
            ->map(function ($note) {
                return [
                    'type' => 'note',
                    'message' => $note->note,
                    'created_at' => $note->created_at
                ];
            });

        /*
        Opportunities
        */
        $opportunities = Opportunity::where(
            'customer_id',
            $customer->id
        )
        ->get()
        ->map(function ($opp) {
            return [
                'type' => 'opportunity',
                'message' => $opp->title . ' created',
                'created_at' => $opp->created_at
            ];
        });

        /*
        Tasks
        */
        $tasks = OpportunityTask::whereHas(
            'opportunity',
            function ($query) use ($customer) {
                $query->where(
                    'customer_id',
                    $customer->id
                );
            }
        )
        ->get()
        ->map(function ($task) {
            return [
                'type' => 'task',
                'message' => $task->title,
                'created_at' => $task->created_at
            ];
        });

        $activities = $activities
            ->merge($notes)
            ->merge($opportunities)
            ->merge($tasks)
            ->sortByDesc('created_at')
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => $activities
        ]);
    }
}

