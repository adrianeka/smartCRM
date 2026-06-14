<?php

namespace App\Services\Dashboard;

use App\Models\Customer;
use App\Models\User;

class MetricsSummaryService
{
    public function summary(User $user): array
    {
        $salesThisMonth = Customer::where('status', 'Customer')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $openOpportunities = Customer::whereIn('status', ['Lead', 'Qualified', 'Proposal', 'Negotiation'])->count();

        $totalCustomers = Customer::count();
        $wonDeals = Customer::where('status', 'Customer')->count();
        $conversionRate = $totalCustomers > 0
            ? round(($wonDeals / $totalCustomers) * 100, 1)
            : 0;

        $openTickets = Customer::whereNull('last_contacted_at')->count();

        return [
            'sales_this_month' => (string) $salesThisMonth,
            'open_opportunities' => (string) $openOpportunities,
            'conversion_rate' => (string) $conversionRate,
            'open_tickets' => (string) $openTickets,
        ];
    }

    public function salesSummary(User $user): array
    {
        $salesThisMonth = Customer::where('status', 'Customer')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $openOpportunities = Customer::whereIn('status', ['Lead', 'Qualified', 'Proposal', 'Negotiation'])->count();

        $totalCustomers = Customer::count();
        $wonDeals = Customer::where('status', 'Customer')->count();
        $conversionRate = $totalCustomers > 0
            ? round(($wonDeals / $totalCustomers) * 100, 1)
            : 0;

        return [
            'sales_this_month' => (string) $salesThisMonth,
            'open_opportunities' => (string) $openOpportunities,
            'conversion_rate' => (string) $conversionRate,
        ];
    }

    public function ticketsSummary(User $user): array
    {
        $openTickets = Customer::whereNull('last_contacted_at')->count();

        $escalatedTickets = Customer::whereNotNull('next_follow_up_at')
            ->where('next_follow_up_at', '<', now())
            ->count();

        return [
            'open_tickets' => (string) $openTickets,
            'escalated_tickets' => (string) $escalatedTickets,
        ];
    }
}
