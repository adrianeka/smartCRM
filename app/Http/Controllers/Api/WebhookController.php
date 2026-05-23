<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebhookLog;

class WebhookController extends Controller
{
    public function receive(Request $request)
    {
        $log = WebhookLog::create([
            'event_type' => $request->input('event_type'),
            'source_module' => $request->input('source_module'),
            'payload' => json_encode($request->all()),
            'status' => 'success',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook received successfully',
            'data' => $log,
        ]);
    }
}
