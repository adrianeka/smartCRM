<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Browser Sessions') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Manage and log out your active sessions on other browsers and devices.') }}
        </p>
    </header>

    <div class="mt-6 max-w-xl text-sm text-gray-600 dark:text-gray-400">
        {{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
    </div>

    <div class="mt-6">
        <form method="POST" action="{{ url('session/logout-others') }}">
            @csrf

            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Password') }}" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                    required
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 mt-6">
                <x-primary-button>
                    {{ __('Log Out Other Browser Sessions') }}
                </x-primary-button>

                @if (session('status') === 'Logged out of other devices successfully.')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Done.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>
