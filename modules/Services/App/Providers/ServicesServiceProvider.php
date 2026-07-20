<?php

namespace Modules\Services\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Kontur\Dashboard\App\Facades\Modules;
use Kontur\Dashboard\App\Modules\Menu\MenuItem;
use Kontur\Dashboard\App\Modules\Module;
use Modules\Menu\App\Facades\MenuBuilder;

class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Modules::register($module = new Module(
            id: 'services',
            name: settings('name', 'services', 'Услуги'),
            sidebarItems: [
                new MenuItem(
                    name: settings('name', 'services', 'Услуги'),
                    routeName: 'admin.services.index',
                    icon: '<i class="bi bi-cash-coin"></i>',
                ),
            ],
        ));

        if ($module->isActive()) {
            MenuBuilder::add([
                new \Modules\Menu\App\Models\MenuItem([
                    'name' => settings('name', 'services', 'Услуги'),
                    'route_name' => 'services.index',
                ]),
            ], 'Модули');
        }

        Blade::componentNamespace('Modules\\Services\\App\\View\\Components', 'services');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'services');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/services'),
        ], 'module-resources');

    }
}
