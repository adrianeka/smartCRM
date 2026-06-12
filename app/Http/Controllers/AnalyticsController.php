<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{



    #[OA\Get(
        path: '/api/v1/analytics/summary',
        summary: 'Analytics Summary',
        tags: ['Analytics'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Analytics KPI Summary'
            )
        ]
    )]
    public function summary()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_customers' => Customer::count(),
                'active_customers' => Customer::where('status', 'Active')->count(),
                'lead_customers' => Customer::where('status', 'Lead')->count(),
                'inactive_customers' => Customer::where('status', 'Inactive')->count(),
            ]
        ]);
    }
}
