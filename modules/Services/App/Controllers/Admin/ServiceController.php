<?php

namespace Modules\Services\App\Controllers\Admin;

use Galtsevt\LaravelSeo\App\Facades\Seo;
use Galtsevt\LaravelStorage\App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Request;
use Modules\Services\App\Models\Service;
use Modules\Services\App\Requests\StoreRequest;
use Modules\Services\App\Requests\UpdateRequest;
use Modules\Services\App\Resources\ServiceResource;
use Modules\Storage\App\Resources\ImageResource;

class ServiceController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        Seo::breadcrumbs()->add(settings('name', 'services', 'Услуги'), route('admin.services.index'));
    }

    public function index(): \Inertia\Response
    {
        Seo::metaData()->setTitle(settings('name', 'services', 'Услуги'));

        return inertia('Modules/Services/Index', [
            'filters' => Request::all('search', 'active'),
            'services' => ServiceResource::collection(
                Service::query()
                    ->filter(Request::only('search', 'active'))
                    ->sort(Request::only('order_by', 'order_direction'))
                    ->orderBy('sort')
                    ->orderBy('id', 'DESC')->paginate(auth()->user()->per_page)->withQueryString()
            ),
        ]);
    }

    public function show(Service $service): \Illuminate\Http\Response
    {
        return response()->view('services::show', [
            'service' => $service,
        ]);
    }

    public function create(): \Inertia\Response
    {
        Seo::metaData()->setTitle('Создание услуги');
        Seo::breadcrumbs()->add('Создать', route('admin.services.create'));
        $service = new Service();

        return inertia('Modules/Services/Edit', [
            'model' => get_class($service),
            'images' => ImageResource::collection(Image::getFreeImages($service, 'editor')),
            'preview' => ($preview = Image::getFreeImages($service, 'preview')?->first()) ?
                new ImageResource($preview) : null,
        ]);
    }

    public function store(StoreRequest $request): ServiceResource
    {
        $service = new Service($request->validated());
        $service->save();

        return new ServiceResource($service);
    }

    public function edit(Service $service): \Inertia\Response
    {
        Seo::metaData()->setTitle('Изменить '.$service->name);
        Seo::breadcrumbs()->add('Изменить '.$service->name, route('admin.services.edit', $service->id));

        $service->load(['seo']);

        return inertia('Modules/Services/Edit', [
            'service' => new ServiceResource($service),
            'model' => get_class($service),
            'images' => ImageResource::collection($service->getImagesByGroup('editor')),
        ]);
    }

    public function update(UpdateRequest $request, Service $service): ServiceResource
    {
        $service->update($request->validated());

        return new ServiceResource($service);
    }

    public function destroy(Service $service): \Illuminate\Http\RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index');
    }

    /**
     * Обновление сортировки услуги
     */
    public function updateSort(\Illuminate\Http\Request $request, Service $service): JsonResponse
    {
        $service['sort'] = $request->input(key: 'sort');
        $service->save();

        return response()->json(['success' => true]);
    }

    /**
     * Получение записей согласно заданной сортировки
     */
    public function getServicesSort(\Illuminate\Http\Request $request): JsonResponse
    {
        $sort = $request->input(key: 'sort');
        $page = $request->input('page', 1);
        $perPage = auth()->user()->per_page ?? 10;

        $query = Service::query()
            ->filter(Request::only('search', 'active'))
            ->sort(Request::only('order_by', 'order_direction'))
            ->orderBy('sort', $sort)
            ->orderBy('id', $sort);

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $services = ServiceResource::collection(
            $query->paginate($perPage)->withQueryString()
        );

        return response()->json(['services' => $services]);
    }
}
