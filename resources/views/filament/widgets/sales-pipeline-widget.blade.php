<x-filament-widgets::widget class="h-full" style="align-self: stretch; height: 100%;">
    <x-filament::section heading="Sales Pipeline" style="height: 100%;">
        <div style="display: flex; flex-direction: column; gap: 24px; margin-top: 8px;">
            @foreach($pipelines as $pipeline)
                <div>
                    <div style="font-size: 0.875rem; font-weight: 500; margin-bottom: 8px;" class="text-gray-500 dark:text-gray-400">
                        {{ $pipeline['stage'] }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="flex: 1; height: 8px; border-radius: 4px; position: relative; overflow: hidden; background-color: rgba(128, 128, 128, 0.2);">
                            <div style="width: {{ $pipeline['percentage'] }}%; height: 100%; border-radius: 4px; background-color: #0ea5e9;"></div>
                        </div>
                        <div style="font-size: 0.875rem; width: 60px; text-align: right;" class="text-gray-500 dark:text-gray-400">
                            {{ $pipeline['deals'] }} Deals
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
