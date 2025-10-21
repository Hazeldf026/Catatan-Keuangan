<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        Paginator::defaultView('vendor.pagination.custom-pagination');

        view()->addNamespace('personal', resource_path('views/personal/pages'));
        view()->addNamespace('group', resource_path('views/group/pages'));

        Blade::anonymousComponentPath(resource_path('views/personal/components'), 'personal');
        Blade::anonymousComponentPath(resource_path('views/group/components'), 'group');
    }
}
