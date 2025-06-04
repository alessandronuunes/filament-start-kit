<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\URL;
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
    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
        
        Cashier::useCustomerModel(Team::class);
        
        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin();
        });
    }
}
