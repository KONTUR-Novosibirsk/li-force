<?php

namespace Galtsevt\AppConf\app\Services;

use DirectoryIterator;
use Galtsevt\AppConf\app\Models\Setting;
use Galtsevt\LaravelSeo\App\Facades\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ConfigService
{
    protected ?array $formElementContainers = null;

    protected string $path = 'settings/factory/';

    protected string $groupName = 'main';

    public function setGroup($name): void
    {
        if ($config = config('admin_settings.groups.'.$name)) {
            $this->path = $config['path'];
            $this->groupName = $name;
            Seo::metaData()->setTitle('Настройки '.$config['name']);
        }
    }

    public function save(Request $request): void
    {
        $rules = [];
        // extract validation rules from form elements object
        foreach ($this->getFormElementContainers() as $container) {
            if ($container->isVisible()) {
                $rules = array_merge($rules, $container->getAllValidationRules());
            }
        }
        $data = $request->validate($rules);
        // send data for modification before save it
        foreach ($this->getFormElementContainers() as $container) {
            if ($container->isVisible()) {
                $container->beforeSave($data);
            }
        }

        $this->saveToDB($data);
    }

    public function getFormElementContainers(): array
    {
        if (! $this->formElementContainers) {
            $this->sort($this->buildFormElementContainers());
        }

        return $this->sort($this->formElementContainers);
    }

    public function buildFormElementContainers(): array
    {
        $files = [];
        foreach (new DirectoryIterator(app_path($this->path)) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            $config = require $fileInfo->getPathname();
            if (isset($config['rights']) && $config['rights'] == 'dev') {
                if (Auth::user()['role'] == 5) {
                    $this->addToFormElementContainer(config: $config, fileInfo: $fileInfo);
                }
            } else {
                $this->addToFormElementContainer(config: $config, fileInfo: $fileInfo);
            }
        }

        return $this->formElementContainers;
    }

    /**
     * Добавляет элементы в контейнер
     */
    private function addToFormElementContainer(array $config, DirectoryIterator $fileInfo): void
    {
        $filename = $fileInfo->getBasename('.'.$fileInfo->getExtension());
        if (isset($config['name'])) {
            $this->formElementContainers[$filename] = new FormElementsContainer(
                key: $filename,
                name: $config['name'],
                visible: $config['visible'] ?? null,
                elements: $config['data'],
                groupName: $this->groupName,
                sort: $config['sort'] ?? 1000,
            );
            if (! $this->formElementContainers[$filename]->isVisible()) {
                unset($this->formElementContainers[$filename]);
            }
        }
    }

    public function storeAppearance(array $data): bool
    {
        if ($data['favicon']) {
            $filename = 'favicon.'.$data['favicon']->extension();
            if (is_file(public_path($filename))) {
                unlink(public_path($filename));
            }
            $data['favicon']->move(public_path(), $filename);
            $result['favicon'] = $filename.'?'.time();
        }
        if (isset($result)) {
            $this->saveToDB($result);
        }

        return true;
    }

    private function sort(array $formElementContainers): array
    {
        uasort($formElementContainers, function (FormElementsContainer $a, FormElementsContainer $b) {
            return $a->getSort() <=> $b->getSort();
        });

        return $formElementContainers;
    }

    private function saveToDB(array $data): void
    {
        Cache::forget('admin_settings'.$this->groupName);
        Cache::forget('admin_settings_all');
        Setting::saveData($data, $this->groupName);
    }
}
