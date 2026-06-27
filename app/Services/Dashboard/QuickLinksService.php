<?php

namespace App\Services\Dashboard;

class QuickLinksService
{
    public function forRole(?string $role): array
    {
        $allLinks = [
            // Existing modules (halaman sudah ada)
            'users' => ['title' => 'Users', 'icon' => 'heroicon-s-users', 'color' => 'white', 'bg' => '#9a3412', 'url' => '/admin/users'],
            'customers' => ['title' => 'Customers', 'icon' => 'heroicon-s-user-group', 'color' => 'white', 'bg' => '#0ea5e9', 'url' => '/admin/customers'],
            'webhooks' => ['title' => 'Webhook Logs', 'icon' => 'heroicon-s-rectangle-stack', 'color' => 'white', 'bg' => '#4b5563', 'url' => '/admin/webhook-logs'],
            'reports' => ['title' => 'Audit Log', 'icon' => 'heroicon-s-document-chart-bar', 'color' => 'white', 'bg' => '#eab308', 'url' => '/admin/activity-logs'],

            // BRD module links (placeholder — halaman belum ada, URL disesuaikan saat modul siap)
            'leads' => ['title' => 'Leads & Pipeline', 'icon' => 'heroicon-s-funnel', 'color' => 'white', 'bg' => '#16a34a', 'url' => '/admin/customers?status=Lead'],
            'campaigns' => ['title' => 'Campaigns', 'icon' => 'heroicon-s-megaphone', 'color' => 'white', 'bg' => '#9333ea', 'url' => '/admin/customers?source=Campaign'],
            'tickets' => ['title' => 'Tickets', 'icon' => 'heroicon-s-ticket', 'color' => 'white', 'bg' => '#dc2626', 'url' => '/admin/customers?status=Active'],
            'calendar' => ['title' => 'Calendar', 'icon' => 'heroicon-s-calendar-days', 'color' => 'white', 'bg' => '#0891b2', 'url' => '/admin/customers?filter=upcoming'],
            'import_export' => ['title' => 'Import / Export', 'icon' => 'heroicon-s-arrow-down-tray', 'color' => 'white', 'bg' => '#ea580c', 'url' => '/admin/customers'],
            'analytics' => ['title' => 'Analytics', 'icon' => 'heroicon-s-presentation-chart-line', 'color' => 'white', 'bg' => '#2563eb', 'url' => '/admin'],
        ];

        $links = match ($role) {
            'super_admin' => [
                $allLinks['users'],
                $allLinks['customers'],
                $allLinks['leads'],
                $allLinks['campaigns'],
                $allLinks['tickets'],
                $allLinks['reports'],
                $allLinks['webhooks'],
                $allLinks['analytics'],
            ],
            'sales' => [
                $allLinks['customers'],
                $allLinks['leads'],
                $allLinks['calendar'],
            ],
            'marketing' => [
                $allLinks['customers'],
                $allLinks['campaigns'],
                $allLinks['import_export'],
            ],
            'support' => [
                $allLinks['customers'],
                $allLinks['tickets'],
            ],
            'manager' => [
                $allLinks['customers'],
                $allLinks['leads'],
                $allLinks['reports'],
                $allLinks['analytics'],
            ],
            default => [
                $allLinks['customers'],
            ],
        };

        return $links;
    }
}
