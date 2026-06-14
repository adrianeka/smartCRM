<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

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
            ),
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
            ],
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
            ),
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
            'data' => $growth,
        ]);
    }

    #[OA\Get(
        path: '/api/v1/analytics/customer-status',
        summary: 'Customer Status Distribution',
        tags: ['Analytics'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Customer status distribution data'
            ),
        ]
    )]
    public function customerStatus()
    {
        $data = Customer::selectRaw('status as label, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
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
            ),
        ]
    )]
    public function customerGrowthTrend()
    {
        $data = Customer::whereNotNull('created_at')
            ->selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total
        ')
            ->groupByRaw('
            YEAR(created_at),
            MONTH(created_at)
        ')
            ->orderByRaw('
            YEAR(created_at),
            MONTH(created_at)
        ')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date(
                        'M Y',
                        mktime(0, 0, 0, $item->month, 1, $item->year)
                    ),
                    'total' => $item->total,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $data,
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
            ),
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
            ],
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
            ),
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

    #[OA\Get(
        path: '/api/v1/analytics/export/csv',
        summary: 'Export Analytics CSV',
        tags: ['Analytics'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Analytics CSV export'
            ),
        ]
    )]
    public function exportCsv()
    {
        $filename = 'analytics.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Total Customers',
                Customer::count(),
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    #[OA\Get(
        path: '/api/v1/analytics/role-dashboard',
        summary: 'Role Based Analytics',
        tags: ['Analytics'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Role dashboard analytics'
            ),
        ]
    )]
    public function roleDashboard(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'role' => $user->roles->pluck('name'),
            'dashboard_type' => $user->hasRole('super_admin')
                    ? 'manager'
                    : 'sales',
        ]);
    }
}
