<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ProcessWebhookJob;
use OpenApi\Attributes as OA;

class WebhookController extends Controller
{

#[OA\Post(
    path: "/api/v1/webhook/receive",
    summary: "Receive External Webhook",
    tags: ["Webhook"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "event", type: "string", example: "customer.created"),
                new OA\Property(property: "customer_id", type: "integer", example: 1)
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: "Webhook accepted"
        )
    ]
)]
    public function receive(Request $request)
    {
        ProcessWebhookJob::dispatch($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Webhook queued successfully',
        ]);
    }
}
