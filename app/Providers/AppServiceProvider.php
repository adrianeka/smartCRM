<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use App\Listeners\LogFailedLogin;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;
use App\Models\Customer;
use App\Models\WebhookLog;
use App\Observers\CustomerObserver;
use App\Observers\WebhookLogObserver;
use App\Policies\ActivityPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\RolePolicy;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class,
            LogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability, $args) {
            if (isset($args[0]) && ($args[0] === Customer::class || $args[0] instanceof Customer)) {
                return null;
            }

            return $user->hasRole('super_admin') ? true : null;
        });

        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);

        Customer::observe(CustomerObserver::class);
        WebhookLog::observe(WebhookLogObserver::class);

        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(Logout::class, LogSuccessfulLogout::class);
        Event::listen(Failed::class, LogFailedLogin::class);
    }
}
