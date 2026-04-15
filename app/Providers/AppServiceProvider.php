<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Product;
use App\Models\IncomingInvoice;
use App\Policies\ProductPolicy;
use App\Policies\IncomingInvoicePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

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

        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(IncomingInvoice::class, IncomingInvoicePolicy::class);
    }
}
