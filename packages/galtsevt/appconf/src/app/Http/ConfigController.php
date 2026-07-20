<?php

namespace Galtsevt\AppConf\app\Http;

use App\Http\Controllers\Controller;
use Galtsevt\AppConf\app\Resources\FormElementContainerResource;
use Galtsevt\AppConf\app\Services\ConfigService;
use Galtsevt\LaravelSeo\App\Facades\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class ConfigController extends Controller
{
    public function index(ConfigService $service, ?string $name = null): Response
    {
        Seo::metaData()->setTitle('Настройки');
        $service->setGroup($name ?? 'main');
        Seo::breadcrumbs()->add('Настройки');

        return inertia('Modules/Settings/Index', [
            'formElementContainers' => FormElementContainerResource::collection($service->getFormElementContainers()),
            'group' => $name,
            'envArray' => Auth::user()['role'] == 5 ? getenv() : null,
        ]);
    }

    public function save(Request $request, ConfigService $service, ?string $name = null): bool
    {
        $service->setGroup($name ?? 'main');
        $service->save($request);

        return true;
    }
}
