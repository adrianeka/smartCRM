<x-filament-widgets::widget class="h-full" style="align-self: stretch; height: 100%;">
    <x-filament::section heading="Quick Links" style="height: 100%;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px;">
            @php
                $user = auth()->user();
                $allLinks = [
                    'users' => ['title' => 'Users', 'icon' => 'heroicon-s-users', 'color' => 'white', 'bg' => '#9a3412', 'url' => '/admin/users'],
                    'customers' => ['title' => 'Customers', 'icon' => 'heroicon-s-user-group', 'color' => 'white', 'bg' => '#0ea5e9', 'url' => '/admin/customers'],
                    'leads' => ['title' => 'Leads', 'icon' => 'heroicon-s-identification', 'color' => 'white', 'bg' => '#a855f7', 'url' => '#'],
                    'opportunities' => ['title' => 'Opportunities', 'icon' => 'heroicon-s-currency-dollar', 'color' => 'white', 'bg' => '#22c55e', 'url' => '#'],
                    'calendar' => ['title' => 'Calendar', 'icon' => 'heroicon-s-calendar', 'color' => 'white', 'bg' => '#4b5563', 'url' => '#'],
                    'campaigns' => ['title' => 'Campaigns', 'icon' => 'heroicon-s-megaphone', 'color' => 'white', 'bg' => '#ec4899', 'url' => '#'],
                    'tickets' => ['title' => 'Tickets', 'icon' => 'heroicon-s-ticket', 'color' => 'white', 'bg' => '#f97316', 'url' => '#'],
                    'knowledge_base' => ['title' => 'Knowledge Base', 'icon' => 'heroicon-s-book-open', 'color' => 'white', 'bg' => '#6366f1', 'url' => '#'],
                    'reports' => ['title' => 'Reports', 'icon' => 'heroicon-s-document-chart-bar', 'color' => 'white', 'bg' => '#eab308', 'url' => '/admin/activity-logs'],
                    'approvals' => ['title' => 'Approvals', 'icon' => 'heroicon-s-check-badge', 'color' => 'white', 'bg' => '#ef4444', 'url' => '#'],
                ];

                if ($user?->hasRole('super_admin') || $user?->hasRole('admin')) {
                    // Super Admin manages system configurations, users, roles, and overall monitoring (Reports)
                    // Excludes Customers since they don't have access to customer data
                    $links = [
                        $allLinks['users'],
                        $allLinks['reports'],
                        $allLinks['approvals']
                    ];
                } elseif ($user?->hasRole('Sales')) {
                    // Sales manages leads, opportunities, pipelines, and customers
                    $links = [
                        $allLinks['customers'],
                        $allLinks['leads'],
                        $allLinks['opportunities'],
                        $allLinks['calendar']
                    ];
                } elseif ($user?->hasRole('Marketing')) {
                    // Marketing manages campaigns, calendar, customers, and reports
                    $links = [
                        $allLinks['customers'],
                        $allLinks['campaigns'],
                        $allLinks['calendar'],
                        $allLinks['reports']
                    ];
                } elseif ($user?->hasRole('Support')) {
                    // Support manages tickets, knowledge base, and customers
                    $links = [
                        $allLinks['tickets'],
                        $allLinks['knowledge_base'],
                        $allLinks['customers']
                    ];
                } elseif ($user?->hasRole('Manager/Analyst')) {
                    // Manager/Analyst accesses dashboards, reports, and insights
                    $links = [
                        $allLinks['reports'],
                        $allLinks['approvals']
                    ];
                } else {
                    $links = array_values($allLinks);
                }
            @endphp

            @foreach($links as $link)
                <a href="{{ $link['url'] }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 12px; text-decoration: none; transition: background-color 0.2s;" class="border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5">
                    <div style="background-color: {{ $link['bg'] }}; border-radius: 8px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <x-filament::icon
                            icon="{{ $link['icon'] }}"
                            style="width: 20px; height: 20px; color: {{ $link['color'] }};"
                        />
                    </div>
                    <span style="font-size: 0.875rem; font-weight: 500;" class="text-gray-700 dark:text-gray-200">{{ $link['title'] }}</span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
