<x-filament-widgets::widget class="h-full" style="align-self: stretch; height: 100%;">
    <x-filament::section heading="Quick Links" style="height: 100%;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px;">
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
