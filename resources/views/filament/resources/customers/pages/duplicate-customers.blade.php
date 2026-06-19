<x-filament-panels::page>
    @php
        $candidates = $this->getDuplicateCandidates();
    @endphp

    <div class="space-y-4">
        <div class="rounded-lg border border-warning-200 bg-warning-50 p-4 text-sm text-warning-900 dark:border-warning-800 dark:bg-warning-950 dark:text-warning-100">
            Halaman ini menampilkan kandidat customer duplikat berdasarkan kesamaan email, nomor telepon, nama, dan perusahaan.
            Gunakan saat demo untuk menunjukkan proses deduplikasi dan smart merge tanpa membuka JSON API.
        </div>

        @forelse ($candidates as $candidate)
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">
                            Skor Kemiripan: {{ $candidate['score'] }}%
                        </div>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach ($candidate['reasons'] as $reason)
                                <span class="rounded-full bg-primary-100 px-2 py-1 text-xs font-medium text-primary-700 dark:bg-primary-500/20 dark:text-primary-200">
                                    {{ $reason }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <x-filament::button
                        color="warning"
                        icon="heroicon-o-arrows-right-left"
                        wire:click="mergePair({{ $candidate['primary']->id }}, {{ $candidate['duplicate']->id }})"
                        wire:confirm="Gabungkan customer duplikat ini ke customer utama?"
                    >
                        Merge ke Customer Utama
                    </x-filament::button>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @foreach (['Customer Utama' => $candidate['primary'], 'Kandidat Duplikat' => $candidate['duplicate']] as $label => $customer)
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-white/10">
                            <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                {{ $label }}
                            </div>
                            <div class="text-base font-semibold text-gray-950 dark:text-white">
                                {{ $customer->full_name }}
                            </div>
                            <dl class="mt-3 space-y-1 text-sm text-gray-600 dark:text-gray-300">
                                <div><span class="font-medium">Kode:</span> {{ $customer->customer_code }}</div>
                                <div><span class="font-medium">Email:</span> {{ $customer->email }}</div>
                                <div><span class="font-medium">Telepon:</span> {{ $customer->phone }}</div>
                                <div><span class="font-medium">Perusahaan:</span> {{ $customer->company_name ?: '-' }}</div>
                                <div><span class="font-medium">Status:</span> {{ $customer->status }}</div>
                                <div><span class="font-medium">PIC:</span> {{ $customer->assignedUser?->name ?: 'Belum ditugaskan' }}</div>
                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white p-8 text-center shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="text-base font-semibold text-gray-950 dark:text-white">Tidak ada kandidat duplikat</div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    Buat dua customer dengan nama dan nomor telepon yang sama untuk mencoba fitur ini.
                </p>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
