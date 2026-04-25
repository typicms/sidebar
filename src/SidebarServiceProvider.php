<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Sidebar;

use Illuminate\Support\ServiceProvider;

class SidebarServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/admin', 'admin');

        $this->publishes([
            __DIR__.'/../resources/views/admin/sidebar' => resource_path('views/admin/sidebar'),
        ], ['typicms-views', 'typicms-admin-views', 'typicms-admin-sidebar-views']);

        $this->app->singleton(SidebarManager::class);
    }
}
