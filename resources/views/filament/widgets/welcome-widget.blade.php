<x-filament-widgets::widget>
    <div x-data="{ showWelcome: true }" x-show="showWelcome" style="background-color: #3b82f6; color: white; border-radius: 16px; padding: 24px; position: relative; overflow: hidden; font-family: ui-sans-serif, system-ui, sans-serif;">
        
        <!-- Close Button -->
        <button @click="showWelcome = false" style="position: absolute; top: 16px; right: 16px; background: transparent; border: none; cursor: pointer; color: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; padding: 4px; border-radius: 4px;">
            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <!-- Content Flex Container -->
        <div style="display: flex; gap: 24px; align-items: flex-start;">
            <div style="background-color: rgba(255,255,255,0.15); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 32px; height: 32px; color: white;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.829 1.58-1.992a4.5 4.5 0 003.169-4.226C18.999 7.625 15.875 4.5 12 4.5s-7 3.125-7 7.08m12.96 2.756c-.118.1-.24.19-.364.275m-11.192 0c-.124-.085-.246-.175-.364-.275" />
                </svg>
            </div>
            <div style="flex: 1;">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 6px 0; line-height: 1.2;">
                    Welcome back, {{ auth()->user()?->name ?? 'Admin' }}!
                </h2>
                <p style="font-size: 0.875rem; color: rgba(255,255,255,0.8); margin: 0 0 24px 0;">
                    You have full access to all system modules and settings.
                </p>
                <div style="background-color: rgba(255,255,255,0.1); border-radius: 12px; padding: 20px;">
                    <p style="font-size: 0.875rem; font-weight: 600; margin: 0 0 12px 0;">Quick Start Tips:</p>
                    <ul style="margin: 0; padding-left: 24px; font-size: 0.875rem; color: rgba(255,255,255,0.8); line-height: 1.8;">
                        <li>Review system health in the admin panel</li>
                        <li>Manage user permissions and roles</li>
                        <li>Monitor overall team performance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
