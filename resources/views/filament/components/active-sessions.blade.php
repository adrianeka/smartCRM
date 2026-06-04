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

<div style="display: flex; flex-direction: column; gap: 16px;">
    {{-- Session List --}}
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @forelse ($sessions as $session)
            <div style="display: flex; align-items: flex-start; gap: 16px; border-radius: 12px; border: 1px solid #e5e7eb; background: #fff; padding: 16px;">
                {{-- Device Icon --}}
                <div style="flex-shrink: 0; border-radius: 8px; background: #f3f4f6; padding: 10px; display: flex; align-items: center; justify-content: center;">
                    @if ($session->is_mobile)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6b7280" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6b7280" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z" />
                        </svg>
                    @endif
                </div>

                {{-- Session Info --}}
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span style="font-size: 14px; font-weight: 600; color: #111827;">
                            {{ $session->browser }} — {{ $session->os }}
                        </span>
                        @if ($session->is_current)
                            <span style="display: inline-flex; align-items: center; border-radius: 6px; background: #eff6ff; padding: 2px 8px; font-size: 12px; font-weight: 500; color: #1d4ed8; border: 1px solid #bfdbfe;">
                                Perangkat Ini
                            </span>
                        @endif
                    </div>
                    <p style="margin-top: 4px; font-size: 12px; color: #6b7280;">
                        {{ $session->ip_address }} &bull; Terakhir aktif {{ $session->last_active }}
                    </p>
                </div>

                {{-- Status Indicator --}}
                <div style="flex-shrink: 0; padding-top: 4px;">
                    @if ($session->is_current)
                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);"></span>
                    @else
                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #d1d5db;"></span>
                    @endif
                </div>
            </div>
        @empty
            <div style="border-radius: 12px; border: 1px solid #e5e7eb; background: #fff; padding: 24px; text-align: center;">
                <p style="font-size: 14px; color: #6b7280;">Tidak ada sesi aktif yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- Logout Other Devices --}}
    @if ($otherSessionsCount > 0)
        <div x-data="{ showModal: false }" style="padding-top: 4px;">
            <button
                type="button"
                x-on:click="showModal = true"
                style="display: inline-flex; align-items: center; gap: 8px; border-radius: 8px; background: #dc2626; color: #fff; padding: 10px 20px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.1);"
                onmouseover="this.style.background='#b91c1c'"
                onmouseout="this.style.background='#dc2626'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                Keluarkan {{ $otherSessionsCount }} Perangkat Lain
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
                style="position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(0,0,0,0.5);"
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
                    style="width: 100%; max-width: 420px; border-radius: 16px; background: #fff; padding: 28px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);"
                    x-on:click.stop
                >
                    {{-- Modal Header --}}
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="flex-shrink: 0; border-radius: 50%; background: #fef2f2; padding: 10px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#dc2626" style="width: 22px; height: 22px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0;">
                            Konfirmasi Keluarkan Perangkat
                        </h3>
                    </div>

                    {{-- Modal Body --}}
                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px; line-height: 1.5;">
                        Semua sesi aktif di perangkat lain akan dikeluarkan. Masukkan kata sandi Anda untuk melanjutkan.
                    </p>

                    <form method="POST" action="{{ route('session.logout-others') }}">
                        @csrf

                        <div style="margin-bottom: 20px;">
                            <label for="logout-password" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">
                                Kata Sandi
                            </label>
                            <input
                                id="logout-password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi Anda"
                                style="display: block; width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px; outline: none; box-sizing: border-box; transition: border-color 0.15s;"
                                onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 2px rgba(99,102,241,0.2)'"
                                onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                            >
                            @error('password')
                                <p style="margin-top: 6px; font-size: 13px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Modal Actions --}}
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                            <button
                                type="button"
                                x-on:click="showModal = false"
                                style="padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; color: #374151; background: #fff; border: 1px solid #d1d5db; cursor: pointer; transition: background 0.15s;"
                                onmouseover="this.style.background='#f9fafb'"
                                onmouseout="this.style.background='#fff'"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                style="padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; color: #fff; background: #dc2626; border: none; cursor: pointer; transition: background 0.15s;"
                                onmouseover="this.style.background='#b91c1c'"
                                onmouseout="this.style.background='#dc2626'"
                            >
                                Ya, Keluarkan Semua
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div style="display: flex; align-items: center; gap: 8px; border-radius: 12px; border: 1px solid #bbf7d0; background: #f0fdf4; padding: 12px 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#16a34a" style="width: 20px; height: 20px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
            <p style="font-size: 14px; font-weight: 500; color: #15803d; margin: 0;">
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
            style="display: flex; align-items: center; gap: 8px; border-radius: 12px; border: 1px solid #bbf7d0; background: #f0fdf4; padding: 12px 16px;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#16a34a" style="width: 20px; height: 20px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p style="font-size: 14px; font-weight: 500; color: #15803d; margin: 0;">
                {{ session('status') }}
            </p>
        </div>
    @endif
</div>
