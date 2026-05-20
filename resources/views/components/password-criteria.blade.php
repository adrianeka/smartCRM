<div class="mt-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300">
    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Kriteria Kata Sandi:</p>
    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2.5">
        <li class="flex items-center transition-colors duration-200" :class="{'text-indigo-600 dark:text-indigo-400': password.length >= 8}">
            <svg x-cloak x-show="password.length >= 8" class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <svg x-show="password.length < 8" class="w-5 h-5 mr-2.5 flex-shrink-0 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle></svg>
            <span>Minimal 8 karakter</span>
        </li>
        <li class="flex items-center transition-colors duration-200" :class="{'text-indigo-600 dark:text-indigo-400': password.match(/[a-z]/) && password.match(/[A-Z]/)}">
            <svg x-cloak x-show="password.match(/[a-z]/) && password.match(/[A-Z]/)" class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <svg x-show="!(password.match(/[a-z]/) && password.match(/[A-Z]/))" class="w-5 h-5 mr-2.5 flex-shrink-0 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle></svg>
            <span>Kombinasi huruf besar dan kecil</span>
        </li>
        <li class="flex items-center transition-colors duration-200" :class="{'text-indigo-600 dark:text-indigo-400': password.match(/[0-9]|[^a-zA-Z0-9]/)}">
            <svg x-cloak x-show="password.match(/[0-9]|[^a-zA-Z0-9]/)" class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <svg x-show="!(password.match(/[0-9]|[^a-zA-Z0-9]/))" class="w-5 h-5 mr-2.5 flex-shrink-0 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle></svg>
            <span>Minimal satu angka atau simbol</span>
        </li>
    </ul>
</div>
