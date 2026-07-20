<?php

namespace Modules\Integrations\App\Providers;

use Illuminate\Support\ServiceProvider;

class IntegrationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(base_path('modules/Integrations/config/ozon.php'), 'ozon');
        $this->mergeConfigFrom(base_path('modules/Integrations/config/wb.php'), 'wb');
    }
}
