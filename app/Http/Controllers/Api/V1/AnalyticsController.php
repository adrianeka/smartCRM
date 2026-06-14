<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Services\Analytics\AnalyticsFilterData;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends BaseApiController
{
    public function __construct(private readonly AnalyticsService $analytics) {}

    #[OA\Get(
        path: '/api/v1/analytics/dashboard',
        summary: 'Get complete analytics dashboard data',
        security: [['sanctum' => []]],
        tags: ['Analytics'],
        responses: [new OA\Response(response: 200, description: 'Analytics dashboard data')],
    )]
    public function dashboard(Request $request)
    {
        return $this->successResponse(
            $this->analytics->dashboard($this->user($request), AnalyticsFilterData::fromRequest($request)),
            'Analytics dashboard retrieved successfully.',
        );
    }

    #[OA\Get(
        path: '/api/v1/analytics/kpis',
        summary: 'Get analytics KPI summary',
        security: [['sanctum' => []]],
        tags: ['Analytics'],
        responses: [new OA\Response(response: 200, description: 'Analytics KPI summary')],
    )]
    public function kpis(Request $request)
    {
        return $this->successResponse(
            $this->analytics->kpis($this->user($request), AnalyticsFilterData::fromRequest($request)),
            'Analytics KPIs retrieved successfully.',
        );
    }

    #[OA\Get(
        path: '/api/v1/analytics/revenue-trend',
        summary: 'Get revenue trend',
        security: [['sanctum' => []]],
        tags: ['Analytics'],
        responses: [new OA\Response(response: 200, description: 'Revenue trend data')],
    )]
    public function revenueTrend(Request $request)
    {
        return $this->successResponse(
            $this->analytics->revenueTrend($this->user($request), AnalyticsFilterData::fromRequest($request)),
            'Revenue trend retrieved successfully.',
        );
    }

    #[OA\Get(
        path: '/api/v1/analytics/sales-funnel',
        summary: 'Get sales funnel',
        security: [['sanctum' => []]],
        tags: ['Analytics'],
        responses: [new OA\Response(response: 200, description: 'Sales funnel data')],
    )]
    public function salesFunnel(Request $request)
    {
        return $this->successResponse(
            $this->analytics->salesFunnel($this->user($request), AnalyticsFilterData::fromRequest($request)),
            'Sales funnel retrieved successfully.',
        );
    }

    #[OA\Get(
        path: '/api/v1/analytics/by-product',
        summary: 'Get revenue by product',
        security: [['sanctum' => []]],
        tags: ['Analytics'],
        responses: [new OA\Response(response: 200, description: 'Revenue grouped by product')],
    )]
    public function revenueByProduct(Request $request)
    {
        return $this->successResponse(
            $this->analytics->revenueByProduct($this->user($request), AnalyticsFilterData::fromRequest($request)),
            'Product analytics retrieved successfully.',
        );
    }

    #[OA\Get(
        path: '/api/v1/analytics/by-region',
        summary: 'Get revenue by region',
        security: [['sanctum' => []]],
        tags: ['Analytics'],
        responses: [new OA\Response(response: 200, description: 'Revenue grouped by region')],
    )]
    public function revenueByRegion(Request $request)
    {
        return $this->successResponse(
            $this->analytics->revenueByRegion($this->user($request), AnalyticsFilterData::fromRequest($request)),
            'Regional analytics retrieved successfully.',
        );
    }

    #[OA\Get(
        path: '/api/v1/analytics/sales-performance',
        summary: 'Get sales-person performance',
        security: [['sanctum' => []]],
        tags: ['Analytics'],
        responses: [new OA\Response(response: 200, description: 'Sales performance data')],
    )]
    public function salesPerformance(Request $request)
    {
        return $this->successResponse(
            $this->analytics->salesPerformance($this->user($request), AnalyticsFilterData::fromRequest($request)),
            'Sales performance retrieved successfully.',
        );
    }

    #[OA\Get(
        path: '/api/v1/analytics/sales-performance/export/csv',
        summary: 'Export sales performance CSV',
        security: [['sanctum' => []]],
        tags: ['Analytics'],
        responses: [new OA\Response(response: 200, description: 'CSV report download')],
    )]
    public function exportSalesPerformance(Request $request): StreamedResponse
    {
        $rows = $this->analytics->salesPerformance(
            $this->user($request),
            AnalyticsFilterData::fromRequest($request),
        );

        return response()->streamDownload(function () use ($rows): void {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, ['SmartCRM Sales Performance Report']);
            fputcsv($stream, ['Sales Person', 'Total Deals', 'Won Deals', 'Conversion Rate (%)', 'Revenue (IDR)']);

            foreach ($rows as $row) {
                fputcsv($stream, [
                    $row['sales_person'],
                    $row['total_deals'],
                    $row['won_deals'],
                    $row['conversion_rate'],
                    $row['revenue'],
                ]);
            }

            fclose($stream);
        }, 'smartcrm-sales-performance-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function user(Request $request): User
    {
        /** @var User $user */
        $user = $request->user();

        return $user;
    }
}
