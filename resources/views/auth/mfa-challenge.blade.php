<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('We have sent a verification code to your email. Please enter the code below to proceed.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('mfa.challenge.verify') }}">
        @csrf

        <!-- Code -->
        <div>
            <x-input-label for="code" :value="__('Verification Code')" />

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
                }" class="flex gap-2 sm:gap-3 mt-3">
                
                <input x-ref="input0" type="text" inputmode="numeric" maxlength="1" class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-semibold rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" x-model="code[0]" @input="handleInput($event, 0)" @keydown="handleKeydown($event, 0)" @paste.prevent="handlePaste($event)" autofocus>
                <input x-ref="input1" type="text" inputmode="numeric" maxlength="1" class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-semibold rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" x-model="code[1]" @input="handleInput($event, 1)" @keydown="handleKeydown($event, 1)" @paste.prevent="handlePaste($event)">
                <input x-ref="input2" type="text" inputmode="numeric" maxlength="1" class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-semibold rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" x-model="code[2]" @input="handleInput($event, 2)" @keydown="handleKeydown($event, 2)" @paste.prevent="handlePaste($event)">
                <input x-ref="input3" type="text" inputmode="numeric" maxlength="1" class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-semibold rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" x-model="code[3]" @input="handleInput($event, 3)" @keydown="handleKeydown($event, 3)" @paste.prevent="handlePaste($event)">
                <input x-ref="input4" type="text" inputmode="numeric" maxlength="1" class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-semibold rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" x-model="code[4]" @input="handleInput($event, 4)" @keydown="handleKeydown($event, 4)" @paste.prevent="handlePaste($event)">
                <input x-ref="input5" type="text" inputmode="numeric" maxlength="1" class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-semibold rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" x-model="code[5]" @input="handleInput($event, 5)" @keydown="handleKeydown($event, 5)" @paste.prevent="handlePaste($event)">
                
                <input id="code" type="hidden" name="code" :value="code.join('')" required />
            </div>

            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>

    <form method="POST" action="{{ route('mfa.challenge.send') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            {{ __('Resend Code') }}
        </button>
    </form>
</x-guest-layout>
