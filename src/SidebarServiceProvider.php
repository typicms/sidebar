<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Sidebar;

use Illuminate\Support\ServiceProvider;

class SidebarServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'sidebar');

        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/sidebar')], 'typicms-views');

        $this->app->singleton(SidebarManager::class);
    }
}
