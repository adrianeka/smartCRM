<x-filament-widgets::widget class="h-full" style="align-self: stretch; height: 100%;">
    <x-filament::section heading="Quick Links" style="height: 100%;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px;">
            @php
                $user = auth()->user();
                $allLinks = [
                    'contacts' => ['title' => 'Contacts', 'icon' => 'heroicon-s-user-group', 'color' => 'white', 'bg' => '#0ea5e9'],
                    'deals' => ['title' => 'Deals', 'icon' => 'heroicon-s-currency-dollar', 'color' => 'white', 'bg' => '#22c55e'],
                    'calendar' => ['title' => 'Calendar', 'icon' => 'heroicon-s-calendar', 'color' => 'white', 'bg' => '#4b5563'],
                    'reports' => ['title' => 'Reports', 'icon' => 'heroicon-s-document-chart-bar', 'color' => 'white', 'bg' => '#eab308'],
                    'team' => ['title' => 'Team', 'icon' => 'heroicon-s-users', 'color' => 'white', 'bg' => '#9a3412'],
                    'approvals' => ['title' => 'Approvals', 'icon' => 'heroicon-s-check-badge', 'color' => 'white', 'bg' => '#ef4444'],
                ];
                
                if ($user?->hasRole('super_admin') || $user?->hasRole('admin')) {
                    $links = array_values($allLinks);
                } elseif ($user?->hasRole('sales')) {
                    $links = [$allLinks['contacts'], $allLinks['deals'], $allLinks['calendar']];
                } elseif ($user?->hasRole('marketing')) {
                    $links = [$allLinks['contacts'], $allLinks['calendar'], $allLinks['reports']];
                } elseif ($user?->hasRole('support')) {
                    $links = [$allLinks['contacts'], $allLinks['team']];
                } elseif ($user?->hasRole('manager')) {
                    $links = [$allLinks['reports'], $allLinks['team'], $allLinks['approvals']];
                } else {
                    $links = array_values($allLinks);
                }
            @endphp

            @foreach($links as $link)
                <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 12px; text-decoration: none; transition: background-color 0.2s;" class="border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5">
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
