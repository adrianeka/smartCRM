<div x-data="{
        password: '',
        get isLengthValid() { return this.password.length >= 8; },
        get hasUpperAndLower() { return /[a-z]/.test(this.password) && /[A-Z]/.test(this.password); },
        get hasNumberOrSymbol() { return /[\d\W]/.test(this.password); }
    }"
    x-init="
        let input = document.getElementById('form.password') || document.getElementById('data.password') || document.querySelector('input[type=\'password\']');
        if (input) {
            password = input.value;
            const updatePassword = (e) => { password = e.target.value; };
            input.addEventListener('input', updatePassword);
            input.addEventListener('change', updatePassword);
            setInterval(() => {
                if (input.value !== password) {
                    password = input.value;
                }
            }, 300);
        }
    "
    style="margin-top: 0.5rem; padding: 0.75rem; border-radius: 0.5rem;"
    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700"
>
    <div class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Kriteria Kata Sandi:</div>
    <ul style="display: flex; flex-direction: column; gap: 0.5rem;">
        <li style="display: flex; align-items: center; gap: 0.5rem;" class="text-sm" :class="isLengthValid ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400'">
            <x-filament::icon icon="heroicon-m-check-circle" style="width: 1.25rem; height: 1.25rem;" x-show="isLengthValid" />
            <div style="width: 1.25rem; height: 1.25rem; border-radius: 9999px; border-width: 2px;" class="border-gray-300 dark:border-gray-600 flex items-center justify-center" x-show="!isLengthValid"></div>
            Minimal 8 karakter
        </li>
        <li style="display: flex; align-items: center; gap: 0.5rem;" class="text-sm" :class="hasUpperAndLower ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400'">
            <x-filament::icon icon="heroicon-m-check-circle" style="width: 1.25rem; height: 1.25rem;" x-show="hasUpperAndLower" />
            <div style="width: 1.25rem; height: 1.25rem; border-radius: 9999px; border-width: 2px;" class="border-gray-300 dark:border-gray-600 flex items-center justify-center" x-show="!hasUpperAndLower"></div>
            Kombinasi huruf besar dan kecil
        </li>
        <li style="display: flex; align-items: center; gap: 0.5rem;" class="text-sm" :class="hasNumberOrSymbol ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400'">
            <x-filament::icon icon="heroicon-m-check-circle" style="width: 1.25rem; height: 1.25rem;" x-show="hasNumberOrSymbol" />
            <div style="width: 1.25rem; height: 1.25rem; border-radius: 9999px; border-width: 2px;" class="border-gray-300 dark:border-gray-600 flex items-center justify-center" x-show="!hasNumberOrSymbol"></div>
            Minimal satu angka atau simbol
        </li>
    </ul>
</div>
