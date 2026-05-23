<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ProcessWebhookJob;

class WebhookController extends Controller
{
    public function receive(Request $request)
    {
        ProcessWebhookJob::dispatch($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Webhook queued successfully',
        ]);
    }
}
