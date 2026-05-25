<x-guest-layout>
    <!-- Header with Icon -->
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mb-5">
            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-neutral-900">Atur Ulang Kata Sandi</h1>
        <p class="mt-2 text-sm text-neutral-500 leading-relaxed">
            Buat kata sandi baru yang kuat untuk akun SmartCRM Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" x-data="{ password: '', showPassword: false, showConfirmPassword: false }">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address (hidden, auto-filled) -->
        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi Baru')" />
            <div class="relative mt-1.5">
                <x-text-input id="password" class="block w-full pr-10" x-bind:type="showPassword ? 'text' : 'password'" name="password" x-model="password" required autocomplete="new-password" placeholder="••••••••" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-neutral-400 hover:text-neutral-600 focus:outline-none transition-colors">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />

            <div class="relative mt-1.5">
                <x-text-input id="password_confirmation" class="block w-full pr-10"
                                    x-bind:type="showConfirmPassword ? 'text' : 'password'"
                                    name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-neutral-400 hover:text-neutral-600 focus:outline-none transition-colors">
                    <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showConfirmPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-password-criteria />

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button>
                Simpan Kata Sandi
            </x-primary-button>
        </div>
    </form>

    <!-- Back to Login -->
    <p class="mt-6 text-center text-sm text-neutral-500">
        <a href="{{ route('login') }}" class="font-semibold text-primary-500 hover:text-primary-600 transition-colors inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Halaman Masuk
        </a>
    </p>
</x-guest-layout>
