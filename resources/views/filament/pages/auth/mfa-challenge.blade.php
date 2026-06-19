<x-filament-panels::page.simple>
    @if (session('status'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .otp-input {
            width: 3.5rem !important;
            height: 4rem !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            border: 1.5px solid #9ca3af !important;
            background-color: #ffffff !important;
            color: #1f2937 !important;
            font-size: 1.5rem !important;
            font-weight: 600 !important;
            text-align: center !important;
            padding: 0 !important;
            transition: all 0.15s ease-in-out !important;
        }
        .otp-input::placeholder {
            color: #718096 !important; /* blue-gray placeholder dot */
            font-size: 1.85rem !important;
            opacity: 1 !important;
            line-height: 3.8rem !important;
            text-align: center !important;
        }
        .dark .otp-input {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
            color: #ffffff !important;
        }
        .dark .otp-input::placeholder {
            color: #9ca3af !important;
        }
        .otp-input:focus {
            border-color: #d97706 !important; /* primary color matching amber */
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.2) !important;
            outline: none !important;
        }
        .btn-verify-container {
            display: flex !important;
            justify-content: center !important;
            width: 100% !important;
            margin-top: 1.5rem !important;
        }
        .btn-verify-container button,
        .btn-verify-container a {
            width: 100% !important;
            justify-content: center !important;
            display: flex !important;
        }
        .footer-container {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            width: 100% !important;
            margin-top: 1.5rem !important;
            gap: 0.5rem !important;
        }
        .footer-container p {
            color: #6b7280 !important;
            font-size: 0.875rem !important;
            margin: 0 !important;
        }
        .dark .footer-container p {
            color: #9ca3af !important;
        }
        .btn-resend {
            background: none !important;
            border: none !important;
            padding: 0 !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: #d97706 !important;
            cursor: pointer !important;
            transition: color 0.15s ease-in-out !important;
            text-decoration: none !important;
        }
        .btn-resend:hover {
            color: #b45309 !important;
            text-decoration: underline !important;
        }
    </style>

    <form method="POST" action="{{ route('mfa.challenge.verify') }}" class="space-y-6">
        @csrf

        <!-- OTP Code -->
        <div x-data="{
                code: ['', '', '', '', '', ''],
                handleInput(e, index) {
                    const val = e.target.value.replace(/[^0-9]/g, '');
                    this.code[index] = val.slice(-1); 
                    e.target.value = this.code[index];
                    
                    if (this.code[index] && index < 5) {
                        setTimeout(() => {
                            const inputs = document.querySelectorAll('.otp-input');
                            if (inputs[index + 1]) inputs[index + 1].focus();
                        }, 10);
                    }
                },
                handleKeydown(e, index) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        setTimeout(() => {
                            const inputs = document.querySelectorAll('.otp-input');
                            if (inputs[index - 1]) inputs[index - 1].focus();
                        }, 10);
                    }
                },
                handlePaste(e) {
                    const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                    if (paste) {
                        for(let i = 0; i < paste.length; i++) {
                            this.code[i] = paste[i];
                        }
                        const focusIndex = paste.length < 6 ? paste.length : 5;
                        setTimeout(() => {
                            const inputs = document.querySelectorAll('.otp-input');
                            if (inputs[focusIndex]) inputs[focusIndex].focus();
                        }, 10);
                    }
                }
            }">

            <div class="otp-container">
                <template x-for="(digit, index) in code" :key="index">
                    <input type="text" inputmode="numeric" maxlength="1" placeholder="•"
                        class="otp-input"
                        x-model="code[index]"
                        @input="handleInput($event, index)"
                        @keydown="handleKeydown($event, index)"
                        @paste.prevent="handlePaste($event)"
                        x-bind:autofocus="index === 0">
                </template>
            </div>

            <input id="code" type="hidden" name="code" :value="code.join('')" required />
        </div>

        <div class="btn-verify-container">
            <x-filament::button type="submit">
                Verifikasi
            </x-filament::button>
        </div>
    </form>

    <div class="footer-container">
        <p>Tidak menerima kode?</p>
        <form method="POST" action="{{ route('mfa.challenge.send') }}">
            @csrf
            <button type="submit" class="btn-resend">
                Kirim Ulang Kode
            </button>
        </form>
    </div>
</x-filament-panels::page.simple>
