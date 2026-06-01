<x-guest-layout>
    <!-- Header with Icon -->
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mb-5">
            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-neutral-900">Verifikasi Dua Faktor</h1>
        <p class="mt-2 text-sm text-neutral-500 leading-relaxed">
            Masukkan 6 digit kode yang telah kami kirimkan ke email Anda untuk memverifikasi akun Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('mfa.challenge.verify') }}">
        @csrf

        <!-- OTP Code -->
        <div>
            <div x-data="{
                    code: ['', '', '', '', '', ''],
                    handleInput(e, index) {
                        const val = e.target.value;
                        if (val && index < 5) {
                            this.$refs['input' + (index + 1)].focus();
                        }
                    },
                    handleKeydown(e, index) {
                        if (e.key === 'Backspace' && !this.code[index] && index > 0) {
                            this.$refs['input' + (index - 1)].focus();
                        }
                    },
                    handlePaste(e) {
                        const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\s/g, '').slice(0, 6);
                        if (paste) {
                            for(let i = 0; i < paste.length; i++) {
                                this.code[i] = paste[i];
                            }
                            const focusIndex = paste.length < 6 ? paste.length : 5;
                            setTimeout(() => this.$refs['input' + focusIndex].focus(), 10);
                        }
                    }
                }" class="flex justify-center gap-3 mt-2">

                <template x-for="(digit, index) in code" :key="index">
                    <input :x-ref="'input' + index" type="text" inputmode="numeric" maxlength="1"
                        class="otp-input"
                        x-model="code[index]"
                        @input="handleInput($event, index)"
                        @keydown="handleKeydown($event, index)"
                        @paste.prevent="handlePaste($event)"
                        x-bind:autofocus="index === 0">
                </template>

                <input id="code" type="hidden" name="code" :value="code.join('')" required />
            </div>

            <x-input-error :messages="$errors->get('code')" class="mt-3 text-center" />
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button>
                Verifikasi
            </x-primary-button>
        </div>
    </form>

    <!-- Resend Code -->
    <div class="mt-6 text-center">
        <p class="text-sm text-neutral-500">Tidak menerima kode?</p>
        <form method="POST" action="{{ route('mfa.challenge.send') }}" class="mt-1">
            @csrf
            <button type="submit" class="text-sm font-semibold text-primary-500 hover:text-primary-600 transition-colors">
                Kirim Ulang Kode
            </button>
        </form>
    </div>
</x-guest-layout>
