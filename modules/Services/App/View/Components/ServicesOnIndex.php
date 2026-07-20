<?php

namespace Modules\Services\App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Modules\Services\App\Models\Service;

class ServicesOnIndex extends Component
{
    public function render(): View|Closure|string
    {
        $services = Service::query()
            ->where([
                ['is_active', 1],
            ])
            ->with([
                'images',
            ])
            ->get();

        return view('services::components.index', [
            'services' => $services,
        ]);
    }
}
