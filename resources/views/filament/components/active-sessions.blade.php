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
        <div style="padding-top: 4px;">
            {{ $this->logoutOtherDevicesAction }}
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
