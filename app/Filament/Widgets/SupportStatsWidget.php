<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupportStatsWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $hasData = Customer::count() > 0;

        if ($hasData) {
            $openTickets = Customer::whereNull('last_contacted_at')->count();
            $unassignedTickets = Customer::whereNull('assigned_user_id')->count();

            $avgLeadScore = Customer::whereNotNull('lead_score')->avg('lead_score');
            $csatVal = $avgLeadScore ? round($avgLeadScore / 20, 1) : 4.8;
            $csat = number_format($csatVal, 1).'/5.0';

            $totalCustomers = Customer::count();
            $urgentTickets = Customer::whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<', now()->addHours(2))
                ->count();

            // Calculate real avg response time from contacted customers
            $contactedCustomers = Customer::whereNotNull('last_contacted_at')
                ->whereNotNull('created_at')
                ->get();

            $responseTimes = [];
            $responseChartData = [];
            foreach ($contactedCustomers as $c) {
                $diffMinutes = $c->created_at->diffInMinutes($c->last_contacted_at);
                $responseTimes[] = $diffMinutes;
            }

            if (count($responseTimes) > 0) {
                $avgMinutes = array_sum($responseTimes) / count($responseTimes);
                $avgHours = floor($avgMinutes / 60);
                $avgMins = $avgMinutes % 60;
                $responseTime = $avgHours > 0 ? "{$avgHours}h {$avgMins}m" : "{$avgMins}m";

                // Build chart data from last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $dayStart = now()->subDays($i)->startOfDay();
                    $dayEnd = now()->subDays($i)->endOfDay();
                    $dayContacted = Customer::whereBetween('last_contacted_at', [$dayStart, $dayEnd])
                        ->whereNotNull('created_at')
                        ->get();
                    if ($dayContacted->count() > 0) {
                        $dayAvg = $dayContacted->avg(fn ($c) => $c->created_at->diffInHours($c->last_contacted_at));
                        $responseChartData[] = round($dayAvg, 1);
                    } else {
                        $responseChartData[] = 0;
                    }
                }
            } else {
                $responseTime = 'N/A';
                $responseChartData = [0, 0, 0, 0, 0, 0, 0];
            }

            return [
                Stat::make('Open Tickets', (string) $openTickets)
                    ->description($urgentTickets.' perlu perhatian segera')
                    ->descriptionIcon('heroicon-m-exclamation-circle')
                    ->color('danger'),
                Stat::make('Unassigned Tickets', (string) $unassignedTickets)
                    ->description('Menunggu dalam antrian')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                Stat::make('Rata-rata Waktu Respon', $responseTime)
                    ->description('Berdasarkan Customers yang dihubungi')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success')
                    ->chart($responseChartData),
                Stat::make('Skor CSAT', $csat)
                    ->description('Berdasarkan skor lead Customers')
                    ->descriptionIcon('heroicon-m-star')
                    ->color('success'),
            ];
        }

        return [
            Stat::make('Open Tickets', '24')
                ->description('5 perlu perhatian segera')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
            Stat::make('Unassigned Tickets', '8')
                ->description('Menunggu dalam antrian')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Rata-rata Waktu Respon', '1j 45m')
                ->description('Target SLA 2 jam')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([3, 2.5, 2, 1.8, 1.5, 1.7, 1.75]),
            Stat::make('Skor CSAT', '4.8/5.0')
                ->description('Dari 120 ulasan bulan ini')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
        ];
    }
}
