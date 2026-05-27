<x-filament-widgets::widget class="h-full" style="align-self: stretch; height: 100%;">
    <x-filament::section heading="Recent Activities" style="height: 100%;">
        <div style="display: flex; flex-direction: column; gap: 24px; margin-top: 8px;">
            @foreach($activities as $activity)
                <div style="display: flex; gap: 16px;">
                    <!-- Dot -->
                    <div style="margin-top: 6px;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background-color: {{ $activity['color'] }};"></div>
                    </div>
                    <!-- Content -->
                    <div style="flex: 1;">
                        <div style="font-size: 1rem; font-weight: 500; margin-bottom: 4px;" class="text-gray-700 dark:text-gray-200">
                            {{ $activity['title'] }}
                        </div>
                        <div style="font-size: 0.875rem;" class="text-gray-500 dark:text-gray-400">
                            {{ $activity['description'] }}<br>
                            {{ $activity['time'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
