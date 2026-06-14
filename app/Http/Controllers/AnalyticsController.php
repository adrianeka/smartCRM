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

    #[OA\Get(
    path: '/api/v1/analytics/customer-growth',
    summary: 'Customer Growth Analytics',
    tags: ['Analytics'],
    responses: [
                new OA\Response(
                    response: 200,
                    description: 'Customer growth chart data'
                )
            ]
        )]

    public function customerGrowth()
        {
            $growth = Customer::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => $item->month,
                        'month_name' => date('F', mktime(0, 0, 0, $item->month, 1)),
                        'total' => $item->total,
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $growth
            ]);
        }


        public function customerStatus()
        {
            $data = Customer::selectRaw('status as label, COUNT(*) as total')
                ->groupBy('status')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }


        #[OA\Get(
            path: '/api/v1/analytics/customer-growth-trend',
            summary: 'Customer Growth Trend',
            tags: ['Analytics'],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Customer growth trend data'
                )
            ]
        )]
        public function customerGrowthTrend()
    {
        $data = Customer::selectRaw("
                MONTH(created_at) as month,
                COUNT(*) as total
            ")
            ->groupByRaw("MONTH(created_at)")
            ->orderByRaw("MONTH(created_at)")
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M', mktime(0, 0, 0, $item->month, 1)),
                    'total' => $item->total,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }


    #[OA\Get(
    path: '/api/v1/analytics/kpi',
    summary: 'Analytics KPI Metrics',
    tags: ['Analytics'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Analytics KPI metrics'
        )
    ]
)]
public function kpi()
{
    $totalCustomers = Customer::count();

    $activeCustomers = Customer::where('status', 'Active')->count();

    $leadCustomers = Customer::where('status', 'Lead')->count();

    $conversionRate = $totalCustomers > 0
        ? round(($activeCustomers / $totalCustomers) * 100, 2)
        : 0;

    return response()->json([
        'status' => 'success',
        'data' => [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'lead_customers' => $leadCustomers,
            'conversion_rate' => $conversionRate,
        ]
    ]);
}


#[OA\Get(
    path: '/api/v1/analytics/customer-growth-filtered',
    summary: 'Customer Growth Filtered',
    tags: ['Analytics'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Filtered customer growth'
        )
    ]
)]
public function customerGrowthFiltered(Request $request)
{
    $query = Customer::query();

    if ($request->start_date) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->end_date) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    return response()->json([
        'status' => 'success',
        'total' => $query->count(),
    ]);
}
}
