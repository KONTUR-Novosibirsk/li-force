<?php

namespace Modules\Services\App\Controllers\Public;

use Galtsevt\LaravelSeo\App\Facades\Seo;
use Modules\Services\App\Models\Service;

class ServiceController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        Seo::breadcrumbs()->add(settings('name', 'services', 'Услуги'), route('services.index'));
    }

    public function index(): \Illuminate\Http\Response
    {
        Seo::metaData()->setTitle(settings('name', 'services', 'Услуги'));

        return response()->view('services::index', [
            'services' => Service::query()->orderBy('sort')->orderBy('id', 'DESC')
                ->paginate(settings('per_page', default: 10)),
        ]);
    }

    public function show(string $alias): \Illuminate\Http\Response
    {
        $service = Service::query()->where('alias', $alias)->firstOrFail();

        Seo::breadcrumbs()->add($service->name, route('services.index'));
        Seo::metaData()->prepare($service)->setTitle($service->name);

        return response()->view('services::show', [
            'service' => $service,
        ]);
    }
}
