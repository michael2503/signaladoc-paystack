<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\Configuration;
use App\Models\Investment;
use App\Models\LandingPage;
use App\Models\MemorialSubscription;
use App\Models\SocialMedia;
use App\Models\UserSubscription;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

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
        view()->composer('*', function($view)
        {
            View::share([

            ]);
        });
        Paginator::useBootstrap();
    }
}
