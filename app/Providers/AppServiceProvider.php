<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use App\Policies\ActivityPolicy;
use Spatie\Permission\Models\Role;
use App\Policies\RolePolicy;
use App\Models\Customer;
use App\Policies\CustomerPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
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
    }
}
