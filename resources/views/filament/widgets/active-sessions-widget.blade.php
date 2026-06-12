<x-filament-widgets::widget>
    <x-filament::section
        icon="heroicon-o-computer-desktop"
        heading="Manajemen Sesi"
        description="Kelola sesi aktif dan perangkat yang terhubung ke akun Anda."
        collapsible
    >
        @include('filament.components.active-sessions')
    </x-filament::section>
</x-filament-widgets::widget>
