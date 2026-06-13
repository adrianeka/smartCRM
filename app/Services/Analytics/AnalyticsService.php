<?php

namespace App\Services\Analytics;

use App\Models\Deal;
use App\Models\User;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function __construct(private readonly AnalyticsQuery $query) {}

    public function dashboard(User $user, AnalyticsFilterData $filters): array
    {
        $deals = $this->deals($user, $filters);

        return [
            'kpis' => $this->kpisFrom($deals),
            'revenue_trend' => $this->revenueTrendFrom($deals),
            'sales_funnel' => $this->salesFunnelFrom($deals),
            'revenue_by_product' => $this->revenueByProductFrom($deals),
            'revenue_by_region' => $this->revenueByRegionFrom($deals),
            'sales_performance' => $this->salesPerformanceFrom($deals),
        ];
    }

    public function kpis(User $user, AnalyticsFilterData $filters): array
    {
        return $this->kpisFrom($this->deals($user, $filters));
    }

    public function revenueTrend(User $user, AnalyticsFilterData $filters): array
    {
        return $this->revenueTrendFrom($this->deals($user, $filters));
    }

    public function salesFunnel(User $user, AnalyticsFilterData $filters): array
    {
        return $this->salesFunnelFrom($this->deals($user, $filters));
    }

    public function revenueByProduct(User $user, AnalyticsFilterData $filters): array
    {
        return $this->revenueByProductFrom($this->deals($user, $filters));
    }

    public function revenueByRegion(User $user, AnalyticsFilterData $filters): array
    {
        return $this->revenueByRegionFrom($this->deals($user, $filters));
    }

    public function salesPerformance(User $user, AnalyticsFilterData $filters): array
    {
        return $this->salesPerformanceFrom($this->deals($user, $filters));
    }

    /**
     * @return Collection<int, Deal>
     */
    private function deals(User $user, AnalyticsFilterData $filters): Collection
    {
        return $this->query->deals($user, $filters)->get();
    }

    /**
     * @param  Collection<int, Deal>  $deals
     */
    private function kpisFrom(Collection $deals): array
    {
        $wonDeals = $deals->where('status', 'Won');
        $closedDeals = $deals->whereIn('status', ['Won', 'Lost']);
        $totalRevenue = (float) $wonDeals->sum('amount');
        $wonCustomerCount = $wonDeals->pluck('customer_id')->unique()->count();
        $customerIds = $deals->pluck('customer_id')->unique();
        $inactiveCustomers = $deals
            ->pluck('customer')
            ->filter()
            ->where('status', 'Inactive')
            ->unique('id')
            ->count();

        return [
            'total_revenue' => round($totalRevenue, 2),
            'conversion_rate' => $closedDeals->isNotEmpty()
                ? round(($wonDeals->count() / $closedDeals->count()) * 100, 1)
                : 0.0,
            'churn_rate' => $customerIds->isNotEmpty()
                ? round(($inactiveCustomers / $customerIds->count()) * 100, 1)
                : 0.0,
            'customer_lifetime_value' => $wonCustomerCount > 0
                ? round($totalRevenue / $wonCustomerCount, 2)
                : 0.0,
            'open_pipeline_value' => round((float) $deals->where('status', 'Open')->sum('amount'), 2),
            'total_deals' => $deals->count(),
        ];
    }

    /**
     * @param  Collection<int, Deal>  $deals
     */
    private function revenueTrendFrom(Collection $deals): array
    {
        return $deals
            ->where('status', 'Won')
            ->groupBy(fn (Deal $deal): string => ($deal->closed_at ?? $deal->created_at)->format('Y-m'))
            ->sortKeys()
            ->map(fn (Collection $items, string $period): array => [
                'period' => $period,
                'label' => $items->first()->closed_at?->format('M Y')
                    ?? $items->first()->created_at->format('M Y'),
                'revenue' => round((float) $items->sum('amount'), 2),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, Deal>  $deals
     */
    private function salesFunnelFrom(Collection $deals): array
    {
        $stages = ['Lead', 'Qualified', 'Proposal', 'Negotiation', 'Won'];

        return collect($stages)
            ->map(fn (string $stage): array => [
                'stage' => $stage,
                'total' => $stage === 'Won'
                    ? $deals->where('status', 'Won')->count()
                    : $deals->where('stage', $stage)->count(),
            ])
            ->all();
    }

    /**
     * @param  Collection<int, Deal>  $deals
     */
    private function revenueByProductFrom(Collection $deals): array
    {
        return $deals
            ->where('status', 'Won')
            ->groupBy(fn (Deal $deal): string => $deal->product?->name ?? 'Tanpa Produk')
            ->map(fn (Collection $items, string $product): array => [
                'product' => $product,
                'revenue' => round((float) $items->sum('amount'), 2),
            ])
            ->sortByDesc('revenue')
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, Deal>  $deals
     */
    private function revenueByRegionFrom(Collection $deals): array
    {
        return $deals
            ->where('status', 'Won')
            ->groupBy(fn (Deal $deal): string => $deal->customer?->province
                ?? $deal->customer?->city
                ?? 'Tidak diketahui')
            ->map(fn (Collection $items, string $region): array => [
                'region' => $region,
                'revenue' => round((float) $items->sum('amount'), 2),
            ])
            ->sortByDesc('revenue')
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, Deal>  $deals
     */
    private function salesPerformanceFrom(Collection $deals): array
    {
        return $deals
            ->groupBy(fn (Deal $deal): string => (string) $deal->owner_id)
            ->map(function (Collection $items): array {
                $wonDeals = $items->where('status', 'Won');
                $closedDeals = $items->whereIn('status', ['Won', 'Lost']);

                return [
                    'owner_id' => $items->first()->owner_id,
                    'sales_person' => $items->first()->owner?->name ?? 'Tidak diketahui',
                    'total_deals' => $items->count(),
                    'won_deals' => $wonDeals->count(),
                    'conversion_rate' => $closedDeals->isNotEmpty()
                        ? round(($wonDeals->count() / $closedDeals->count()) * 100, 1)
                        : 0.0,
                    'revenue' => round((float) $wonDeals->sum('amount'), 2),
                ];
            })
            ->sortByDesc('revenue')
            ->values()
            ->all();
    }
}
