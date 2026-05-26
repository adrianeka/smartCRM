<x-guest-layout>
    <!-- Header with Icon -->
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mb-5">
            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-neutral-900">Lupa Kata Sandi</h1>
        <p class="mt-2 text-sm text-neutral-500 leading-relaxed">
            Masukkan email Anda untuk mendapatkan tautan pengaturan ulang kata sandi.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button>
                Kirim Tautan
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
