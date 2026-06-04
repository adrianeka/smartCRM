@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;

    $currentSessionId = session()->getId();

    $sessions = DB::table('sessions')
        ->where('user_id', auth()->id())
        ->orderByDesc('last_activity')
        ->get()
        ->map(function ($session) use ($currentSessionId) {
            $agent = $session->user_agent ?? '';

            // Parse OS
            $os = 'Tidak Diketahui';
            if (str_contains($agent, 'Windows')) $os = 'Windows';
            elseif (str_contains($agent, 'Macintosh') || str_contains($agent, 'Mac OS')) $os = 'macOS';
            elseif (str_contains($agent, 'iPhone') || str_contains($agent, 'iPad')) $os = 'iOS';
            elseif (str_contains($agent, 'Android')) $os = 'Android';
            elseif (str_contains($agent, 'Linux')) $os = 'Linux';

            // Parse Browser
            $browser = 'Tidak Diketahui';
            if (str_contains($agent, 'Edg/') || str_contains($agent, 'Edge/')) $browser = 'Microsoft Edge';
            elseif (str_contains($agent, 'OPR/') || str_contains($agent, 'Opera')) $browser = 'Opera';
            elseif (str_contains($agent, 'Chrome/') && !str_contains($agent, 'Edg/')) $browser = 'Google Chrome';
            elseif (str_contains($agent, 'Safari/') && !str_contains($agent, 'Chrome/')) $browser = 'Safari';
            elseif (str_contains($agent, 'Firefox/')) $browser = 'Firefox';

            // Determine device type
            $isMobile = str_contains($agent, 'Mobile') || str_contains($agent, 'Android') || str_contains($agent, 'iPhone');

            return (object) [
                'id' => $session->id,
                'ip_address' => $session->ip_address ?? '-',
                'os' => $os,
                'browser' => $browser,
                'is_mobile' => $isMobile,
                'is_current' => $session->id === $currentSessionId,
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });

    $otherSessionsCount = $sessions->where('is_current', false)->count();
@endphp

<div class="space-y-4">
    {{-- Session List --}}
    <div class="space-y-3">
        @forelse ($sessions as $session)
            <div class="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                {{-- Device Icon --}}
                <div class="flex-shrink-0 rounded-lg bg-gray-100 p-2.5 dark:bg-white/10">
                    @if ($session->is_mobile)
                        <x-heroicon-o-device-phone-mobile class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                    @else
                        <x-heroicon-o-computer-desktop class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                    @endif
                </div>

                {{-- Session Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-950 dark:text-white">
                            {{ $session->browser }} — {{ $session->os }}
                        </p>
                        @if ($session->is_current)
                            <span class="inline-flex items-center rounded-md bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30">
                                Perangkat Ini
                            </span>
                        @endif
                    </div>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ $session->ip_address }}</span>
                        <span class="mx-1">•</span>
                        <span>Terakhir aktif {{ $session->last_active }}</span>
                    </p>
                </div>

                {{-- Status Indicator --}}
                <div class="flex-shrink-0">
                    @if ($session->is_current)
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                    @else
                        <span class="inline-flex rounded-full h-3 w-3 bg-gray-300 dark:bg-gray-600"></span>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-gray-200 bg-white p-6 text-center dark:border-white/10 dark:bg-white/5">
                <x-heroicon-o-shield-check class="mx-auto h-8 w-8 text-gray-400" />
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada sesi aktif yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- Logout Other Devices --}}
    @if ($otherSessionsCount > 0)
        <div x-data="{ showModal: false }" class="pt-2">
            <button
                type="button"
                x-on:click="showModal = true"
                class="fi-btn fi-btn-size-md relative inline-flex items-center justify-center gap-1.5 rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm outline-none transition-all duration-75 focus-visible:ring-2 bg-danger-600 text-white hover:bg-danger-500 focus-visible:ring-danger-500/50 dark:bg-danger-500 dark:hover:bg-danger-400"
            >
                <x-heroicon-m-arrow-right-start-on-rectangle class="h-5 w-5" />
                <span>Keluarkan {{ $otherSessionsCount }} Perangkat Lain</span>
            </button>

            {{-- Confirmation Modal --}}
            <div
                x-show="showModal"
                x-cloak
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="background-color: rgba(0,0,0,0.5)"
                x-on:click.self="showModal = false"
            >
                <div
                    x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-gray-900"
                    x-on:click.stop
                >
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-shrink-0 rounded-full bg-danger-100 p-2 dark:bg-danger-500/20">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-danger-600 dark:text-danger-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                            Konfirmasi Keluarkan Perangkat Lain
                        </h3>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-5">
                        Semua sesi aktif di perangkat lain akan dikeluarkan. Masukkan kata sandi Anda untuk melanjutkan.
                    </p>

                    <form method="POST" action="{{ route('session.logout-others') }}">
                        @csrf

                        <div class="mb-5">
                            <label for="logout-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Kata Sandi
                            </label>
                            <input
                                id="logout-password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi Anda"
                                class="fi-input block w-full rounded-lg border-gray-300 shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-primary-500 sm:text-sm"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button
                                type="button"
                                x-on:click="showModal = false"
                                class="fi-btn fi-btn-size-md relative inline-flex items-center justify-center gap-1.5 rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm outline-none transition-all duration-75 focus-visible:ring-2 bg-white text-gray-950 hover:bg-gray-50 ring-1 ring-gray-950/10 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 dark:ring-white/20"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="fi-btn fi-btn-size-md relative inline-flex items-center justify-center gap-1.5 rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm outline-none transition-all duration-75 focus-visible:ring-2 bg-danger-600 text-white hover:bg-danger-500 focus-visible:ring-danger-500/50 dark:bg-danger-500 dark:hover:bg-danger-400"
                            >
                                Ya, Keluarkan Semua
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 p-3 dark:border-green-500/20 dark:bg-green-500/10">
            <x-heroicon-o-shield-check class="h-5 w-5 text-green-600 dark:text-green-400" />
            <p class="text-sm font-medium text-green-700 dark:text-green-400">
                Tidak ada sesi aktif lainnya. Akun Anda aman.
            </p>
        </div>
    @endif

    {{-- Success notification --}}
    @if (session('status'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 p-3 dark:border-green-500/20 dark:bg-green-500/10"
        >
            <x-heroicon-o-check-circle class="h-5 w-5 text-green-600 dark:text-green-400" />
            <p class="text-sm font-medium text-green-700 dark:text-green-400">
                {{ session('status') }}
            </p>
        </div>
    @endif
</div>
