<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LunarService;
use App\Services\HolidayService;
use App\Services\ThemeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LunarService::class);
        $this->app->singleton(HolidayService::class);
        $this->app->singleton(ThemeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}