<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    /**
    * Bootstrap any application services.
    *
    * @noinspection PhpParamsInspection
    */
    public function boot(): void
    {
        Gate::define('manage-inventory', function (User $user) {
            return in_array($user->role, ['admin', 'storekeeper']);
        });

        Gate::define('view-reports', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'storekeeper']);
        });

        Gate::define('access-admin-panel', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
