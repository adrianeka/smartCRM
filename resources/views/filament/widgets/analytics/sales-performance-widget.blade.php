<x-filament-widgets::widget>
    <x-filament::section
        heading="Performa Sales"
        description="Perbandingan jumlah deal, conversion rate, dan revenue per sales person."
        icon="heroicon-o-trophy"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-white/10 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Sales Person</th>
                        <th class="px-4 py-3 text-right">Total Deal</th>
                        <th class="px-4 py-3 text-right">Won</th>
                        <th class="px-4 py-3 text-right">Conversion</th>
                        <th class="px-4 py-3 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse ($rows as $row)
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-4 py-3 font-medium text-gray-950 dark:text-white">{{ $row['sales_person'] }}</td>
                            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">{{ $row['total_deals'] }}</td>
                            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">{{ $row['won_deals'] }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex rounded-full bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary-700 dark:bg-primary-400/10 dark:text-primary-400">
                                    {{ $row['conversion_rate'] }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-success-600 dark:text-success-400">
                                Rp {{ number_format($row['revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data deal pada filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
