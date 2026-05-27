<?php

namespace App\Services\Dashboard;

class QuickLinksService
{
    public function forRole(?string $role): array
    {
        $links = [
            'super_admin' => [
                ['label' => 'Users', 'url' => '/admin/users', 'icon' => 'heroicon-o-users'],
                ['label' => 'Customers', 'url' => '/admin/customers', 'icon' => 'heroicon-o-briefcase'],
            ],
            'sales' => [
                ['label' => 'Customers', 'url' => '/admin/customers', 'icon' => 'heroicon-o-briefcase'],
            ],
            'support' => [
                ['label' => 'Customers', 'url' => '/admin/customers', 'icon' => 'heroicon-o-briefcase'],
            ],
        ];

        return $links[$role] ?? [];
    }
}
