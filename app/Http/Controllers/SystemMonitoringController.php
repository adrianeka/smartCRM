<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;
class SystemMonitoringController extends Controller
{

#[OA\Get(
    path: "/api/v1/monitoring/health",
    summary: "System Health Check",
    tags: ["Monitoring"],
    responses: [
        new OA\Response(
            response: 200,
            description: "System status"
        )
    ]
)]
    public function health()
    {
        return response()->json([
            'status' => 'healthy',
            'server_time' => now(),
            'database' => DB::connection()->getDatabaseName(),
            'application' => config('app.name')
        ]);
    }
}
