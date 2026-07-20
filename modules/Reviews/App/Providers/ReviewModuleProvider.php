<?php

namespace Modules\Reviews\App\Providers;

use Kontur\Dashboard\App\Modules\Menu\MenuItem;
use Kontur\Dashboard\App\Modules\Module;
use Kontur\Dashboard\App\Modules\ModuleProvider;

class ReviewModuleProvider extends ModuleProvider
{
    protected string $name = 'reviews';

    protected function init(): void
    {

    }

    protected function run(): void
    {

    }

    protected function getModule(): Module
    {
        return new Module(
            id: 'reviews',
            name: settings('name', 'reviews', 'Отзывы'),
            sidebarItems: [
                new MenuItem(
                    name: settings('name', 'reviews', 'Отзывы'),
                    routeName: 'admin.review.index',
                    icon: '<i class="bi bi-chat-text-fill"></i>',
                ),
            ],
            widgets: [
            ]
        );
    }

    protected function getDir(): string
    {
        return __DIR__;
    }
}
